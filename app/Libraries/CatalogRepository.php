<?php

namespace App\Libraries;

use CodeIgniter\Database\BaseConnection;
use Throwable;

final class CatalogRepository
{
    private BaseConnection $db;

    private ?bool $catalogReady = null;

    public function __construct(?BaseConnection $db = null)
    {
        $this->db = $db ?? db_connect();
    }

    public function categories(): array
    {
        if (! $this->isCatalogReady()) {
            return CatalogData::categories();
        }

        return $this->db->table('categories')
            ->select('id, name, slug, description, icon, sort_order')
            ->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * @return list<string>
     */
    public function brands(): array
    {
        if (! $this->isCatalogReady()) {
            return CatalogData::brands();
        }

        $rows = $this->db->table('brands')
            ->select('name')
            ->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('name', 'ASC')
            ->get()
            ->getResultArray();

        return array_values(array_map(static fn (array $row): string => $row['name'], $rows));
    }

    public function products(): array
    {
        if (! $this->isCatalogReady()) {
            return array_map([$this, 'normalizeFallbackProduct'], CatalogData::products());
        }

        $rows = $this->baseProductQuery()
            ->orderBy('p.is_featured', 'DESC')
            ->orderBy('p.sort_order', 'ASC')
            ->orderBy('p.id', 'DESC')
            ->get()
            ->getResultArray();

        return array_map([$this, 'hydrateProduct'], $rows);
    }

    public function featuredProducts(int $limit = 4): array
    {
        return array_slice($this->products(), 0, max(0, $limit));
    }

    public function findProduct(string $slug): ?array
    {
        if (! $this->isCatalogReady()) {
            $product = CatalogData::findProduct($slug);

            return $product === null ? null : $this->normalizeFallbackProduct($product);
        }

        $row = $this->baseProductQuery()
            ->where('p.slug', $slug)
            ->get()
            ->getRowArray();

        return $row === null ? null : $this->hydrateProduct($row);
    }

    public function relatedProducts(string $categorySlug, string $excludeSlug, int $limit = 4): array
    {
        if (! $this->isCatalogReady()) {
            $items = array_values(array_filter(
                CatalogData::products(),
                static fn (array $item): bool => $item['category'] === $categorySlug && $item['slug'] !== $excludeSlug
            ));

            return array_map([$this, 'normalizeFallbackProduct'], array_slice($items, 0, $limit));
        }

        $rows = $this->baseProductQuery()
            ->where('c.slug', $categorySlug)
            ->where('p.slug !=', $excludeSlug)
            ->orderBy('p.is_featured', 'DESC')
            ->orderBy('p.sort_order', 'ASC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        return array_map([$this, 'hydrateProduct'], $rows);
    }

    public function usingDatabase(): bool
    {
        return $this->isCatalogReady();
    }

    private function baseProductQuery()
    {
        return $this->db->table('products p')
            ->select([
                'p.id',
                'p.name',
                'p.slug',
                'p.sku',
                'p.short_description',
                'p.description',
                'p.meta',
                'p.icon',
                'p.badge',
                'p.specifications',
                'p.sort_order',
                'p.is_featured',
                'c.slug AS category',
                'c.name AS category_label',
                'b.name AS brand',
                'pi.url AS image_url',
            ])
            ->join('categories c', 'c.id = p.category_id')
            ->join('brands b', 'b.id = p.brand_id', 'left')
            ->join('product_images pi', 'pi.product_id = p.id AND pi.is_primary = 1', 'left')
            ->where('p.is_active', 1)
            ->where('c.is_active', 1);
    }

    private function hydrateProduct(array $row): array
    {
        $specs = [];
        if (! empty($row['specifications'])) {
            $decoded = json_decode((string) $row['specifications'], true);
            if (is_array($decoded)) {
                $specs = $decoded;
            }
        }

        return [
            'id' => isset($row['id']) ? (int) $row['id'] : null,
            'slug' => (string) $row['slug'],
            'sku' => $row['sku'] ?? null,
            'brand' => ! empty($row['brand']) ? (string) $row['brand'] : 'Generic',
            'name' => (string) $row['name'],
            'category' => (string) $row['category'],
            'category_label' => (string) $row['category_label'],
            'meta' => (string) ($row['meta'] ?? ''),
            'icon' => (string) ($row['icon'] ?? 'bi-tools'),
            'badge' => $row['badge'] ?? null,
            'description' => (string) ($row['description'] ?: $row['short_description'] ?: ''),
            'short_description' => (string) ($row['short_description'] ?? ''),
            'specs' => $specs,
            'image_url' => $row['image_url'] ?? null,
            'is_featured' => ! empty($row['is_featured']),
        ];
    }

    private function normalizeFallbackProduct(array $product): array
    {
        $product['id'] = $product['id'] ?? null;
        $product['sku'] = $product['sku'] ?? null;
        $product['short_description'] = $product['short_description'] ?? $product['description'] ?? '';
        $product['image_url'] = $product['image_url'] ?? null;
        $product['is_featured'] = $product['is_featured'] ?? false;

        return $product;
    }

    private function isCatalogReady(): bool
    {
        if ($this->catalogReady !== null) {
            return $this->catalogReady;
        }

        try {
            $this->catalogReady = $this->db->tableExists('categories')
                && $this->db->tableExists('brands')
                && $this->db->tableExists('products')
                && $this->db->tableExists('product_images');
        } catch (Throwable) {
            $this->catalogReady = false;
        }

        return $this->catalogReady;
    }
}
