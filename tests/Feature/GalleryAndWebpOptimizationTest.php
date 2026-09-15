<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Media;
use App\Models\Product;
use App\Models\User;
use App\Services\ImageUploadService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GalleryAndWebpOptimizationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected ImageUploadService $imageService;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->admin = User::factory()->create([
            'email'          => 'admin_test@oceanfresh.com',
            'customer_group' => 'admin',
        ]);

        $this->imageService = app(ImageUploadService::class);
    }

    /**
     * Test uploading a JPEG converts it to .webp preserving exact resolution.
     */
    public function test_jpeg_upload_converts_to_webp_and_preserves_resolution(): void
    {
        $width = 640;
        $height = 480;
        $file = UploadedFile::fake()->image('fresh-salmon.jpg', $width, $height);

        $media = $this->imageService->upload($file, 'products');

        $this->assertDatabaseHas('media', [
            'id'        => $media->id,
            'folder'    => 'products',
            'mime_type' => 'image/webp',
            'width'     => $width,
            'height'    => $height,
        ]);

        $this->assertStringEndsWith('.webp', $media->filename);
        $this->assertEquals($width, $media->width);
        $this->assertEquals($height, $media->height);

        // Verify storage file exists
        Storage::disk('public')->assertExists($media->path);
    }

    /**
     * Test uploading a PNG converts to .webp preserving exact resolution.
     */
    public function test_png_upload_converts_to_webp_and_preserves_resolution(): void
    {
        $width = 500;
        $height = 350;
        $file = UploadedFile::fake()->image('transparent-prawn.png', $width, $height);

        $media = $this->imageService->upload($file, 'gallery');

        $this->assertDatabaseHas('media', [
            'id'        => $media->id,
            'folder'    => 'gallery',
            'mime_type' => 'image/webp',
            'width'     => $width,
            'height'    => $height,
        ]);

        $this->assertStringEndsWith('.webp', $media->filename);
        $this->assertEquals($width, $media->width);
        $this->assertEquals($height, $media->height);
    }

    /**
     * Test admin gallery page loads.
     */
    public function test_admin_can_view_gallery_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.gallery.index'));
        $response->assertStatus(200);
        $response->assertSee('Media Gallery');
        $response->assertSee('100% WebP');
    }

    /**
     * Test uploading an image via gallery controller.
     */
    public function test_admin_can_upload_to_gallery_via_http(): void
    {
        $file = UploadedFile::fake()->image('ocean-cod.jpg', 800, 600);

        $response = $this->actingAs($this->admin)->postJson(route('admin.gallery.upload'), [
            'file'   => $file,
            'folder' => 'gallery',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'message',
            'media' => [
                ['id', 'url', 'path', 'original_name', 'mime_type', 'width', 'height']
            ]
        ]);

        $this->assertDatabaseHas('media', [
            'original_name' => 'ocean-cod.jpg',
            'mime_type'     => 'image/webp',
            'width'         => 800,
            'height'        => 600,
        ]);
    }

    /**
     * Test uploading a large image (e.g. 5MB+) exceeding PHP default 2M limit.
     */
    public function test_admin_can_upload_large_image_exceeding_2mb(): void
    {
        $largeFile = UploadedFile::fake()->image('giant-tuna.jpg', 1920, 1080)->size(5120); // 5MB

        $response = $this->actingAs($this->admin)->postJson(route('admin.gallery.upload'), [
            'files'  => [$largeFile],
            'folder' => 'gallery',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseHas('media', [
            'original_name' => 'giant-tuna.jpg',
            'mime_type'     => 'image/webp',
        ]);
    }

    /**
     * Test gallery API endpoint for picker search and filtering.
     */
    public function test_gallery_picker_api_returns_items(): void
    {
        Media::create([
            'filename'      => 'test1.webp',
            'original_name' => 'premium-lobster.jpg',
            'path'          => 'gallery/test1.webp',
            'mime_type'     => 'image/webp',
            'size'          => 20480,
            'width'         => 1200,
            'height'        => 800,
            'folder'        => 'gallery',
        ]);

        $response = $this->actingAs($this->admin)->getJson(route('admin.gallery.api', ['search' => 'lobster']));

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'original_name' => 'premium-lobster.jpg',
        ]);
    }

    /**
     * Test deleting a media item removes database record and file.
     */
    public function test_admin_can_delete_media_item(): void
    {
        $file = UploadedFile::fake()->image('to-delete.jpg', 400, 400);
        $media = $this->imageService->upload($file, 'gallery');

        $response = $this->actingAs($this->admin)->delete(route('admin.gallery.destroy', $media));

        $response->assertRedirect();
        $this->assertDatabaseMissing('media', ['id' => $media->id]);
    }

    /**
     * Test product store converts uploaded thumbnail to .webp.
     */
    public function test_product_store_converts_thumbnail_to_webp(): void
    {
        $category = Category::create([
            'name'      => 'Test Category',
            'slug'      => 'test-category',
            'is_active' => true,
        ]);

        $thumbnail = UploadedFile::fake()->image('salmon-raw.png', 1024, 768);

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'category_id'        => $category->id,
            'name'               => 'Fresh King Salmon',
            'sku'                => 'SALMON-KING-01',
            'unit'               => '500g pack',
            'retail_price'       => 45.00,
            'walkin_price'       => 40.00,
            'wholesale_price'    => 35.00,
            'stock_quantity'     => 100,
            'moq'                => 1,
            'moq_wholesale'      => 5,
            'moq_trading'        => 20,
            'is_active'          => 1,
            'thumbnail'          => $thumbnail,
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $product = Product::where('sku', 'SALMON-KING-01')->firstOrFail();
        $this->assertNotNull($product->thumbnail);
        $this->assertStringEndsWith('.webp', $product->thumbnail);

        $this->assertDatabaseHas('media', [
            'path'      => $product->thumbnail,
            'mime_type' => 'image/webp',
            'width'     => 1024,
            'height'    => 768,
        ]);
    }

    /**
     * Test product store uses selected gallery image.
     */
    public function test_product_store_with_gallery_thumbnail(): void
    {
        $category = Category::create([
            'name'      => 'Test Crustaceans',
            'slug'      => 'test-crustaceans',
            'is_active' => true,
        ]);

        $media = Media::create([
            'filename'      => 'existing_lobster.webp',
            'original_name' => 'lobster.jpg',
            'path'          => 'products/existing_lobster.webp',
            'mime_type'     => 'image/webp',
            'size'          => 15000,
            'width'         => 800,
            'height'        => 600,
            'folder'        => 'products',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'category_id'       => $category->id,
            'name'              => 'Live Boston Lobster',
            'sku'               => 'LOB-BOS-01',
            'unit'              => '1 piece',
            'retail_price'      => 85.00,
            'walkin_price'      => 80.00,
            'wholesale_price'   => 70.00,
            'stock_quantity'    => 50,
            'moq'               => 1,
            'moq_wholesale'     => 3,
            'moq_trading'       => 10,
            'is_active'         => 1,
            'gallery_thumbnail' => $media->path,
        ]);

        $response->assertRedirect(route('admin.products.index'));

        $product = Product::where('sku', 'LOB-BOS-01')->firstOrFail();
        $this->assertEquals($media->path, $product->thumbnail);
    }

    /**
     * Test category store converts uploaded image to .webp.
     */
    public function test_category_store_converts_image_to_webp(): void
    {
        $image = UploadedFile::fake()->image('shellfish-banner.jpg', 1200, 400);

        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name'      => 'Premium Shellfish',
            'is_active' => 1,
            'image'     => $image,
        ]);

        $response->assertRedirect(route('admin.categories.index'));

        $category = Category::where('name', 'Premium Shellfish')->firstOrFail();
        $this->assertNotNull($category->image);
        $this->assertStringEndsWith('.webp', $category->image);

        $this->assertDatabaseHas('media', [
            'path'      => $category->image,
            'mime_type' => 'image/webp',
            'width'     => 1200,
            'height'    => 400,
        ]);
    }

    /**
     * Test admin can create a new gallery folder.
     */
    public function test_admin_can_create_gallery_folder(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.gallery.folders.create'), [
            'name' => 'Marketing Banners',
        ]);

        $response->assertRedirect(route('admin.gallery.index', ['folder' => 'marketing-banners']));

        $this->assertDatabaseHas('media_folders', [
            'name'      => 'Marketing Banners',
            'slug'      => 'marketing-banners',
            'is_system' => false,
        ]);
    }

    /**
     * Test admin cannot delete system folders.
     */
    public function test_admin_cannot_delete_system_folders(): void
    {
        $systemFolder = \App\Models\MediaFolder::where('slug', 'products')->firstOrFail();

        $response = $this->actingAs($this->admin)->delete(route('admin.gallery.folders.destroy', $systemFolder));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('media_folders', ['slug' => 'products']);
    }

    /**
     * Test admin can delete empty custom folder.
     */
    public function test_admin_can_delete_empty_custom_folder(): void
    {
        $customFolder = \App\Models\MediaFolder::create([
            'name'      => 'Old Promos',
            'slug'      => 'old-promos',
            'is_system' => false,
        ]);

        $response = $this->actingAs($this->admin)->delete(route('admin.gallery.folders.destroy', $customFolder));

        $response->assertRedirect(route('admin.gallery.index'));
        $this->assertDatabaseMissing('media_folders', ['id' => $customFolder->id]);
    }

    /**
     * Test moving an image to another folder.
     */
    public function test_admin_can_move_image_to_another_folder(): void
    {
        $file = UploadedFile::fake()->image('cod-fillet.jpg', 600, 400);
        $media = $this->imageService->upload($file, 'gallery');

        $targetFolder = \App\Models\MediaFolder::where('slug', 'products')->firstOrFail();

        $response = $this->actingAs($this->admin)->post(route('admin.gallery.move', $media), [
            'target_folder' => $targetFolder->slug,
        ]);

        $response->assertSessionHas('success');

        $media->refresh();
        $this->assertEquals('products', $media->folder);
        $this->assertStringStartsWith('products/', $media->path);
    }

    /**
     * Test copying/duplicating an image.
     */
    public function test_admin_can_copy_image(): void
    {
        $file = UploadedFile::fake()->image('tiger-prawns.png', 500, 500);
        $media = $this->imageService->upload($file, 'gallery');

        $response = $this->actingAs($this->admin)->post(route('admin.gallery.copy', $media), [
            'target_folder' => 'categories',
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('media', [
            'original_name' => 'Copy of tiger-prawns.png',
            'folder'        => 'categories',
            'width'         => 500,
            'height'        => 500,
        ]);
    }

    /**
     * Test bulk move media items.
     */
    public function test_admin_can_bulk_move_media_items(): void
    {
        $file1 = UploadedFile::fake()->image('item1.jpg', 400, 300);
        $file2 = UploadedFile::fake()->image('item2.jpg', 400, 300);

        $m1 = $this->imageService->upload($file1, 'gallery');
        $m2 = $this->imageService->upload($file2, 'gallery');

        $response = $this->actingAs($this->admin)->post(route('admin.gallery.bulkMove'), [
            'media_ids'     => [$m1->id, $m2->id],
            'target_folder' => 'products',
        ]);

        $response->assertSessionHas('success');

        $this->assertEquals('products', $m1->fresh()->folder);
        $this->assertEquals('products', $m2->fresh()->folder);
    }

    /**
     * Test bulk delete media items.
     */
    public function test_admin_can_bulk_delete_media_items(): void
    {
        $file1 = UploadedFile::fake()->image('del1.jpg', 200, 200);
        $file2 = UploadedFile::fake()->image('del2.jpg', 200, 200);

        $m1 = $this->imageService->upload($file1, 'gallery');
        $m2 = $this->imageService->upload($file2, 'gallery');

        $response = $this->actingAs($this->admin)->post(route('admin.gallery.bulkDestroy'), [
            'media_ids' => [$m1->id, $m2->id],
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('media', ['id' => $m1->id]);
        $this->assertDatabaseMissing('media', ['id' => $m2->id]);
    }

    /**
     * Test admin can rename a media item.
     */
    public function test_admin_can_rename_media_item(): void
    {
        $file = UploadedFile::fake()->image('old-name.jpg', 600, 400);
        $media = $this->imageService->upload($file, 'gallery');

        $response = $this->actingAs($this->admin)->postJson(route('admin.gallery.rename', $media), [
            'name' => 'Atlantic Cod Fresh Catch',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertEquals('Atlantic Cod Fresh Catch', $media->fresh()->original_name);
    }

    /**
     * Test admin can download a media item.
     */
    public function test_admin_can_download_media_item(): void
    {
        $file = UploadedFile::fake()->image('download-me.jpg', 400, 300);
        $media = $this->imageService->upload($file, 'gallery');

        $response = $this->actingAs($this->admin)->get(route('admin.gallery.download', $media));

        $response->assertStatus(200);
        $response->assertHeader('content-disposition');
    }

    /**
     * Test admin can crop a media item with coordinates.
     */
    public function test_admin_can_crop_media_item_with_coordinates(): void
    {
        $file = UploadedFile::fake()->image('crop-test.jpg', 600, 400);
        $media = $this->imageService->upload($file, 'gallery');

        $response = $this->actingAs($this->admin)->postJson(route('admin.gallery.crop', $media), [
            'x'      => 50,
            'y'      => 50,
            'width'  => 300,
            'height' => 200,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $fresh = $media->fresh();
        $this->assertEquals(300, $fresh->width);
        $this->assertEquals(200, $fresh->height);
    }

    /**
     * Test admin can crop a media item with base64 canvas data.
     */
    public function test_admin_can_crop_media_item_with_base64_data(): void
    {
        $file = UploadedFile::fake()->image('base64-test.jpg', 500, 500);
        $media = $this->imageService->upload($file, 'gallery');

        // Create a simple 200x200 PNG base64 data string
        $img = imagecreatetruecolor(250, 150);
        ob_start();
        imagepng($img);
        $raw = ob_get_clean();
        imagedestroy($img);
        $base64 = 'data:image/png;base64,' . base64_encode($raw);

        $response = $this->actingAs($this->admin)->postJson(route('admin.gallery.crop', $media), [
            'image_data' => $base64,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $fresh = $media->fresh();
        $this->assertEquals(250, $fresh->width);
        $this->assertEquals(150, $fresh->height);
    }

    /**
     * Test admin can delete a product with proper success redirect.
     */
    public function test_admin_can_delete_product(): void
    {
        $product = Product::create([
            'name'            => 'Temp Delete Product',
            'slug'            => 'temp-delete-product',
            'sku'             => 'TEMP-DEL-01',
            'unit'            => 'kg',
            'retail_price'    => 20.00,
            'walkin_price'    => 18.00,
            'wholesale_price' => 15.00,
            'stock_quantity'  => 50,
            'moq'             => 1,
            'moq_wholesale'   => 5,
            'moq_trading'     => 10,
            'is_active'       => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.products.destroy', $product));

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success', 'Product deleted successfully.');
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    /**
     * Test admin can delete category safely dissociating its products and subcategories.
     */
    public function test_admin_can_delete_category_safely(): void
    {
        $parent = Category::create([
            'name'      => 'Parent Cat',
            'slug'      => 'parent-cat',
            'is_active' => true,
        ]);

        $child = Category::create([
            'name'      => 'Child Cat',
            'slug'      => 'child-cat',
            'parent_id' => $parent->id,
            'is_active' => true,
        ]);

        $product = Product::create([
            'name'            => 'Category Product',
            'slug'            => 'category-product',
            'sku'             => 'CAT-PROD-01',
            'category_id'     => $parent->id,
            'unit'            => 'pack',
            'retail_price'    => 10.00,
            'walkin_price'    => 9.00,
            'wholesale_price' => 8.00,
            'stock_quantity'  => 10,
            'moq'             => 1,
            'moq_wholesale'   => 1,
            'moq_trading'     => 1,
            'is_active'       => true,
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.categories.destroy', $parent));

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('categories', ['id' => $parent->id]);
        $this->assertNull($child->fresh()->parent_id);
        $this->assertNull($product->fresh()->category_id);
    }

    /**
     * Test admin can delete media via AJAX JSON request.
     */
    public function test_admin_can_delete_media_item_ajax(): void
    {
        $file = UploadedFile::fake()->image('to-delete.jpg', 300, 300);
        $media = $this->imageService->upload($file, 'gallery');

        $response = $this->actingAs($this->admin)
            ->deleteJson(route('admin.gallery.destroy', $media));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDatabaseMissing('media', ['id' => $media->id]);
        Storage::disk('public')->assertMissing($media->path);
    }

    /**
     * Test moving item to another folder moves the item, removes it from root, and redirects to destination.
     */
    public function test_moving_item_to_another_folder_removes_it_from_root_and_redirects_to_target_folder(): void
    {
        $targetFolder = \App\Models\MediaFolder::firstOrCreate(
            ['slug' => 'promotions'],
            ['name' => 'Promotions', 'is_system' => false]
        );

        $file = UploadedFile::fake()->image('promo-banner.jpg', 600, 300);
        $media = $this->imageService->upload($file, 'gallery');

        // Move to promotions folder
        $response = $this->actingAs($this->admin)
            ->post(route('admin.gallery.move', $media), [
                'target_folder' => 'promotions',
            ]);

        $response->assertRedirect(route('admin.gallery.index', ['folder' => 'promotions']));
        $response->assertSessionHas('success');

        $media->refresh();
        $this->assertEquals('promotions', $media->folder);

        // Verify promotions folder includes promo-banner (consumes session flash message)
        $promoResponse = $this->actingAs($this->admin)->get(route('admin.gallery.index', ['folder' => 'promotions']));
        $promoResponse->assertStatus(200);
        $promoResponse->assertSee($media->original_name);

        // Verify root gallery does not include promo-banner in files
        $rootResponse = $this->actingAs($this->admin)->get(route('admin.gallery.index'));
        $rootResponse->assertStatus(200);
        $rootResponse->assertDontSee($media->original_name);

        // Verify API root does not include it, but API folder does
        $apiRoot = $this->actingAs($this->admin)->getJson(route('admin.gallery.api', ['folder' => 'root']));
        $this->assertFalse(collect($apiRoot->json('data'))->pluck('id')->contains($media->id));

        $apiFolder = $this->actingAs($this->admin)->getJson(route('admin.gallery.api', ['folder' => 'promotions']));
        $this->assertTrue(collect($apiFolder->json('data'))->pluck('id')->contains($media->id));
    }

    /**
     * Test downloading media item returns clean single extension without .jpg.webp.
     */
    public function test_admin_can_download_media_item_with_clean_extension(): void
    {
        $file = UploadedFile::fake()->image('clean-download.png', 400, 300);
        $media = $this->imageService->upload($file, 'gallery');

        $response = $this->actingAs($this->admin)->get(route('admin.gallery.download', ['media' => $media->id, 'filename' => 'test.webp']));
        $response->assertStatus(200);
        $this->assertTrue(str_contains(strtolower($response->headers->get('content-disposition', '')), 'clean-download.webp'));
    }

    /**
     * Test admin can download folder ZIP archive and entries have proper extensions.
     */
    public function test_admin_can_download_folder_zip(): void
    {
        $folder = \App\Models\MediaFolder::firstOrCreate(
            ['slug' => 'seafood-zip-test'],
            ['name' => 'Seafood Zip Test', 'is_system' => false]
        );

        $file1 = UploadedFile::fake()->image('squid.jpg', 300, 300);
        $file2 = UploadedFile::fake()->image('lobster.png', 400, 300);
        $this->imageService->upload($file1, $folder->slug);
        $this->imageService->upload($file2, $folder->slug);

        $zipFilename = 'seafood-zip-test-files.zip';
        $response = $this->actingAs($this->admin)->get(route('admin.gallery.folders.download', ['folder' => $folder->id, 'filename' => $zipFilename]));
        $response->assertStatus(200);
        $this->assertTrue(str_contains(strtolower($response->headers->get('content-type', '')), 'zip'));
        $this->assertTrue(str_contains(strtolower($response->headers->get('content-disposition', '')), 'seafood-zip-test-files.zip'));

        // Verify that entries inside the zip have valid image extensions
        $zipFile = $response->getFile();
        $zip = new \ZipArchive();
        $this->assertTrue($zip->open($zipFile->getPathname()) === true);
        $this->assertEquals(2, $zip->numFiles);
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $this->assertTrue(in_array($ext, ['jpg', 'jpeg', 'png', 'webp']), "ZIP entry {$name} does not have valid image extension");
        }
        $zip->close();
    }

    /**
     * Test admin can download all media as ZIP.
     */
    public function test_admin_can_download_all_media_zip(): void
    {
        $file = UploadedFile::fake()->image('gallery-all-sample.jpg', 300, 300);
        $this->imageService->upload($file, 'gallery');

        $response = $this->actingAs($this->admin)->get(route('admin.gallery.folders.download', 'all'));
        $response->assertStatus(200);
        $this->assertTrue(str_contains(strtolower($response->headers->get('content-type', '')), 'zip'));
    }

    /**
     * Test downloading an empty folder returns redirect back with warning.
     */
    public function test_download_empty_folder_returns_redirect_with_warning(): void
    {
        $emptyFolder = \App\Models\MediaFolder::create([
            'name'      => 'Empty Test Folder',
            'slug'      => 'empty-test-folder-' . time(),
            'is_system' => false,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.gallery.folders.download', $emptyFolder->id));
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}

