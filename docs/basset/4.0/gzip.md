title: Gzip Pre-compression
---
## Gzip Pre-compression

- [Enabling Gzip Pre-compression](#enabling-gzip-precompression)

Using Gzip to compress files is a great way to speed up the loading time of your application. For most users pre-compressing built collections won't be required as compressing assets when they're served with a `.htaccess` is fine. The advantage to pre-compressing assets is being able to use the highest possible compression level. 

> When using pre-compression you should disable any other compression that might be applied as it may have unexpected results.

### <a name="enabling-gzip-precompression"></a> Enabling Gzip Pre-compression

To enable pre-compression globally you can adjust the `gzip` configuration option. Whenever collections are built they will be compressed using the maximum compression level.

To use pre-compression on a per-collection basis simply use the `--gzip` flag when building collections.

<?prettify?>

	$ php artisan basset:build collection --gzip

> Make sure you configure your server to correctly serve the built collections with Gzip.