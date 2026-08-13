# Upload Files

A Laravel package that simplifies the creation of a files and images CRUD using polymorphic relationships (MorphTo / MorphMany), automatically integrating physical storage into Laravel's `Storage` disks and handling the cleanup of residual files.

## Requirements

- PHP 8.1, 8.2 or 8.3
- Laravel 10.x or 11.x

## Installation

1. Install the package via Composer:

```bash
composer require wilbere/upload-files
```

2. Publish the necessary migrations to create the `files` and `images` tables:

```bash
php artisan vendor:publish --tag=upload-file-migration
```

3. Run the migrations against your database:

```bash
php artisan migrate
```

## Step-by-Step Usage

### 1. Prepare your Model

Add the `HasFiles` and/or `HasImages` Traits to any Eloquent model you want to attach files or images to (e.g., a `User`, `Post`, or `Product`).

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Wilbere\UploadFiles\Traits\HasImages;
use Wilbere\UploadFiles\Traits\HasFiles;

class User extends Model
{
    use HasImages, HasFiles;
}
```

### 2. Upload Files and Images

The package provides the `uploadFile()` and `uploadImage()` methods, which handle both saving the file physically using Laravel's native `Storage` system and simultaneously creating the polymorphic record in the database.

```php
use Illuminate\Http\Request;
use App\Models\User;

public function storeAvatar(Request $request, User $user)
{
    $request->validate([
        'avatar' => 'required|image|max:2048'
    ]);

    // Parameters for uploadImage / uploadFile: 
    // 1. UploadedFile instance ($request->file(...))
    // 2. Target folder (Optional - default: 'images' / 'files')
    // 3. Storage Disk (Optional - default: 'public')
    
    $user->uploadImage($request->file('avatar'), 'avatars', 'public');
    
    return back()->with('success', 'Avatar uploaded successfully');
}
```

### 3. Accessing the Records

You can read the files and images by accessing the magic Eloquent relationships provided by the Traits:

```php
// Image Relationships
$image = $user->image; // MorphOne (Gets a single record)
echo $image->url; // Physical path of the file

$images = $user->images; // MorphMany (Gets a collection of records)

// File Relationships
$file = $user->file; // MorphOne
echo $file->name; // Original name
echo $file->path; // Physical path of the file

$files = $user->files; // MorphMany
```

To display images in Blade (make sure you have configured `php artisan storage:link` if you use the `public` disk):
```blade
<img src="{{ Storage::url($user->image->url) }}" alt="Avatar">
```

### 4. Automatic Deletion (Auto-cleanup)

This package includes native events (Booted Observers) in the `File` and `Image` models. 

When calling the `delete()` method on the relationship, the package will automatically find the corresponding physical file on your disk (using `Storage::disk('public')`) and delete it from your server before deleting the database record. No more orphaned files!

```php
// This deletes the database record AND the associated physical file from the disk simultaneously.
$user->image->delete();
```
