# Build System

This project uses Vite as the build tool, replacing the previous Webpack setup for better performance and modern tooling.

## Available Scripts

- `npm run dev` - Start development server with hot reload
- `npm run build` - Production build with optimizations and cache busting
- `npm run build:dev` - Development build without optimizations
- `npm run watch` - Watch mode for development
- `npm run preview` - Preview production build
- `npm test` - Run Jest tests with coverage

## Build Configuration

The build system is configured in `vite.config.js` and includes:

### Entry Points
- **JavaScript/TypeScript**: 12 entry points including core modularity files and module-specific scripts
- **SCSS**: 6 entry points for stylesheets including main styles and module-specific styles

### Output Structure
- **Development**: `dist/js/[name].js` and `dist/css/[name].css`
- **Production**: `dist/js/[name].[hash].js` and `dist/css/[name].[hash].css`

### Cache Busting
- Production builds include content hashes in filenames
- `manifest.json` maps original names to hashed filenames
- Maintains compatibility with existing asset loading systems

### Features
- TypeScript compilation
- SCSS compilation with PostCSS and Autoprefixer
- Source maps in all modes
- Hot Module Replacement in development
- CSS minification and optimization in production

## Development Server

The development server runs on `http://localhost:3000` and supports:
- Hot reload for JavaScript and CSS changes
- Proxy support via `BROWSER_SYNC_PROXY_URL` environment variable
- TypeScript compilation on-the-fly

## Dependencies

The build system requires these core dependencies:
- **vite**: Modern build tool and dev server
- **sass**: SCSS compilation
- **autoprefixer**: CSS vendor prefixes
- **typescript**: TypeScript support

## Migration from Webpack

This setup replaces the previous Webpack configuration while maintaining:
- Same entry points and output structure
- Compatible manifest.json format
- Same build commands (npm scripts)
- All original functionality including cache busting

## Notes

- The `@helsingborg-stad/openstreetmap` dependency is marked as external until authentication is available
- Source maps are generated for debugging
- Tests use Babel for JavaScript/TypeScript compilation