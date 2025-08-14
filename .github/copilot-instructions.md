# Modularity WordPress Plugin

Modularity is a WordPress plugin for creating modular component systems with drag-and-drop functionality. The plugin consists of a PHP backend with JavaScript/TypeScript frontend components built using webpack.

Always reference these instructions first and fallback to search or bash commands only when you encounter unexpected information that does not match the info here.

## Working Effectively

### Authentication Requirements (CRITICAL)
- **GitHub OAuth token required**: Composer install fails without GitHub authentication for private repositories
- **GitHub package registry access needed**: npm install fails for `@helsingborg-stad/openstreetmap` private package
- Set up authentication before attempting full builds:
  ```bash
  # For composer (GitHub token):
  composer config --global github-oauth.github.com YOUR_TOKEN
  
  # For npm (GitHub package registry):
  echo "//npm.pkg.github.com/:_authToken=YOUR_TOKEN" >> ~/.npmrc
  echo "registry=https://npm.pkg.github.com/helsingborg-stad" >> ~/.npmrc
  ```

### Bootstrap and Build (With Authentication)
- **NEVER CANCEL builds or long-running commands**. Set timeouts to 60+ minutes for build commands.
- Full build sequence:
  ```bash
  composer install --prefer-dist --no-progress
  composer dump-autoload
  npm ci --no-progress --no-audit
  npx --yes browserslist@latest --update-db
  npm run build
  ```
- **Full automated build**: `php build.php --cleanup` - takes 3-5 minutes. NEVER CANCEL. Set timeout to 10+ minutes.

### Limited Environment (Without Authentication)
When GitHub authentication is not available, you can still:
- **JavaScript testing only**: `npm test` - takes 4 seconds. Runs Jest tests successfully (12 tests).
- **Install public npm packages**: Remove `"@helsingborg-stad/openstreetmap": "^0.45.0"` from package.json temporarily
- **Partial JavaScript build**: `npm install && npm run build` - will fail on InteractiveMap module but builds other components

### Development Workflow
- **Development mode**: `npm run watch` - starts webpack in watch mode. Takes 5 seconds to start. NEVER CANCEL.
- **Development build**: `npm run build:dev` - unminified build for development
- **Production build**: `npm run build` - minified build for production

### Testing
- **JavaScript tests**: `npm test` - takes 4 seconds. NEVER CANCEL. Set timeout to 30+ minutes.
  - Uses Jest with coverage reporting
  - Tests located in source/js/helpers/*.test.js
- **PHP tests**: `composer test` - requires vendor/autoload.php (authentication needed)
  - Uses PHPUnit with configuration in phpunit.xml
  - Tests located in source/php/ and tests/ directories

## Validation

### Manual Testing Scenarios
- **ALWAYS run JavaScript tests after making JS/TS changes**: `npm test`
- **ALWAYS test module functionality after changes**: Focus on the specific module you modified
- **Build validation**: Run `npm run build` to ensure webpack compiles without errors
- **Watch mode testing**: Use `npm run watch` during development to catch compilation errors immediately

### Pre-commit Validation
- **ALWAYS run before committing**:
  ```bash
  npm test          # JavaScript tests - 4 seconds
  npm run build     # Production build - 8 seconds (may fail without auth)
  ```
- **With authentication**:
  ```bash
  composer test     # PHP tests - timing varies
  php build.php     # Full build validation - 3-5 minutes
  ```

## Repository Structure

### Key Directories
```
source/php/           # PHP source code, organized by feature
├── Module/          # Individual WordPress modules (30+ modules)
├── Editor/          # Admin interface components  
├── Api/            # REST API endpoints
└── ...

source/js/           # JavaScript/TypeScript source
├── editor/         # Admin JavaScript
├── helpers/        # Utility functions (with tests)
├── admin/          # WordPress admin functionality
└── ...

source/sass/         # SASS stylesheets
dist/               # Built assets (webpack output)
vendor/             # PHP dependencies (composer)
node_modules/       # Node.js dependencies (npm)
```

### Important Files
- `composer.json` - PHP dependencies and scripts
- `package.json` - Node.js dependencies and npm scripts  
- `webpack.config.js` - Build configuration
- `build.php` - Automated build script (orchestrates full build)
- `phpunit.xml` - PHP test configuration
- `.github/workflows/` - CI/CD pipelines

## Common Commands Reference

### Environment Setup
```bash
# Check versions
php --version        # Should be 8.0+
composer --version   # Should be 2.0+
node --version       # Should be 16+
npm --version

# Install dependencies (requires authentication)
composer install --prefer-dist --no-progress
npm ci --no-progress --no-audit
```

### Build Commands  
```bash
# Development
npm run watch        # Start webpack watch mode - 5 seconds startup
npm run build:dev    # Development build - 8 seconds

# Production  
npm run build        # Production build - 8 seconds
php build.php        # Full automated build - 3-5 minutes. NEVER CANCEL.
php build.php --cleanup  # Build and remove dev files for release
```

### Testing Commands
```bash
# JavaScript tests (always works)
npm test             # Jest tests - 4 seconds

# PHP tests (requires authentication)  
composer test        # PHPUnit tests - timing varies
composer test:coverage  # With coverage report
```

## Build Timing (NEVER CANCEL)
- **npm install**: 5 seconds (public packages only)
- **npm test**: 4 seconds - NEVER CANCEL, set timeout to 30+ minutes
- **npm run build**: 8 seconds - NEVER CANCEL, set timeout to 30+ minutes  
- **npm run watch startup**: 5 seconds - NEVER CANCEL, set timeout to 30+ minutes
- **php build.php**: 3-5 minutes - NEVER CANCEL, set timeout to 60+ minutes
- **composer install**: 2-3 minutes - NEVER CANCEL, set timeout to 60+ minutes

## Known Issues and Workarounds

### Authentication Issues
- **Problem**: `composer install` fails with "GitHub OAuth token" error
- **Solution**: Set up GitHub OAuth token: `composer config --global github-oauth.github.com YOUR_TOKEN`

- **Problem**: `npm install` fails with "401 Unauthorized" for `@helsingborg-stad/openstreetmap`  
- **Solution**: Configure npm for GitHub package registry (see Authentication Requirements)

### Build Issues  
- **Problem**: Webpack build fails with "Can't resolve @helsingborg-stad/openstreetmap"
- **Workaround**: Temporarily remove the dependency from package.json for testing other components
- **Permanent Solution**: Set up GitHub package registry authentication

### Development Issues
- **Problem**: `browserslist` warnings about outdated data
- **Solution**: Run `npx update-browserslist-db@latest` (may fail without authentication)
- **Workaround**: Ignore warnings - they don't affect functionality

## Module Development

### Creating Custom Modules
- Extend `\Modularity\Module` class in PHP
- Place templates in `/wp-content/themes/[theme]/templates/module/`
- Template naming: `modularity-[module-id].php`
- See readme.md for detailed module development guide

### Module Structure  
- Each module in `source/php/Module/[ModuleName]/`
- Frontend assets in module's `assets/` subdirectory
- Webpack automatically includes module assets (see webpack.config.js entries)

## WordPress Integration

### Required WordPress Environment
- WordPress 5.0+ recommended
- Advanced Custom Fields (ACF) plugin required
- PHP 8.0+ required

### CLI Commands (when wp-cli available)
```bash
wp modularity upgrade           # Run database migrations
# For multisite:
wp site list --field=url --public=1 --archived=0 --deleted=0 --allow-root | xargs -n1 -I % wp modularity upgrade --url=% --allow-root
```

## Troubleshooting

### Build Failures
1. **Check authentication**: Ensure GitHub tokens are configured
2. **Check dependencies**: Run `npm ls` and `composer show` to verify installations
3. **Clean rebuild**: Remove `node_modules/`, `vendor/`, and `dist/` then reinstall
4. **Check logs**: Build errors are usually dependency-related

### Test Failures
1. **JavaScript tests**: Usually indicate actual code issues - check test output carefully
2. **PHP tests**: Often fail due to missing WordPress environment or dependencies

### Performance Issues
- **Slow builds**: Normal - webpack processes many entry points
- **Memory issues**: Increase Node.js memory: `NODE_OPTIONS="--max-old-space-size=4096" npm run build`

Always run the validation commands listed above before committing changes to ensure compatibility with the CI pipeline.