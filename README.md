## Cloudinary

Useful helpers functions to be used with Cloudinary.

```bash
composer require ctbuh/cloudinary-php "dev-main"
```

### Apply transformations to an existing Cloudinary URL

```php
echo cl_url_update_transformations("https://res.cloudinary.com/ctbuh2/image/upload/v1601396721/sample.jpg", 'w_350,h_250');

// https://res.cloudinary.com/ctbuh2/image/upload/w_350,h_250/v1601396721/sample.jpg

echo cl_url_update_transformations("https://res.cloudinary.com/ctbuh2/image/upload/h_200/v1601396721/sample.jpg", 'w_350');

// https://res.cloudinary.com/ctbuh2/image/upload/h_200,w_350/v1601396721/sample.jpg

```
