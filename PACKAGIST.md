# Packagist Publishing Guide / មគ្គុទេសក៍ការផ្សព្វផ្សាយ Packagist

This guide explains how to publish this package to Packagist.
មគ្គុទេសក៍នេះពន្យល់អំពីរបៀបផ្សព្វផ្សាយ package នេះទៅ Packagist។

## Prerequisites / តម្រូវការមុន

1. **GitHub Repository** - Package must be in a Git repository / Package ត្រូវតែនៅក្នុង Git repository
2. **Packagist Account** - Create account at https://packagist.org / បង្កើត account នៅ https://packagist.org
3. **GitHub Token** (optional) - For auto-updates / សម្រាប់ auto-updates

## Steps / ជំហាន

### 1. Create GitHub Repository / បង្កើត GitHub Repository

```bash
cd packages/leap-lyhour/lighthouse-graphql-sanctum-auth
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/lyhourdev/lighthouse-graphql-sanctum-auth.git
git push -u origin main
```

### 2. Create Release Tag / បង្កើត Release Tag

```bash
# Create version tag
git tag -a v1.0.0 -m "Initial release"
git push origin v1.0.0
```

### 3. Submit to Packagist / ដាក់ជូន Packagist

1. Go to https://packagist.org/packages/submit
2. Enter repository URL: `https://github.com/lyhourdev/lighthouse-graphql-sanctum-auth`
3. Click "Check" and then "Submit"

### 4. Configure Auto-Update (Optional) / កំណត់ Auto-Update (ជម្រើស)

1. Go to your Packagist account settings
2. Add GitHub token for auto-updates
3. Enable "Update" button on package page

## Package Checklist / បញ្ជីពិនិត្យ Package

Before publishing, ensure you have:

មុនពេលផ្សព្វផ្សាយ, ធានាថាអ្នកមាន:

- ✅ `composer.json` with proper configuration / `composer.json` ជាមួយការកំណត់ត្រឹមត្រូវ
- ✅ `LICENSE` file (MIT) / File `LICENSE` (MIT)
- ✅ `README.md` with documentation / `README.md` ជាមួយ documentation
- ✅ `.gitignore` file / File `.gitignore`
- ✅ `CHANGELOG.md` (optional but recommended) / `CHANGELOG.md` (ជម្រើស ប៉ុន្តែបានណែនាំ)
- ✅ Service provider properly registered / Service provider ត្រូវបានចុះឈ្មោះត្រឹមត្រូវ
- ✅ All dependencies listed in `composer.json` / Dependencies ទាំងអស់ត្រូវបានរាយក្នុង `composer.json`
- ✅ Tests (if applicable) / Tests (ប្រសិនបើមាន)

## Versioning / កំណត់ Version

Follow [Semantic Versioning](https://semver.org/):
គោរពតាម [Semantic Versioning](https://semver.org/):

- **MAJOR** version for incompatible API changes / ការផ្លាស់ប្តូរ API ដែលមិនឆបគ្នា
- **MINOR** version for new functionality in a backwards compatible manner / Functionality ថ្មីដែលឆបគ្នាជាមួយ version ចាស់
- **PATCH** version for backwards compatible bug fixes / ការកែបញ្ហា bugs ដែលឆបគ្នាជាមួយ version ចាស់

Example: `1.0.0`, `1.1.0`, `1.1.1`
ឧទាហរណ៍: `1.0.0`, `1.1.0`, `1.1.1`

## After Publishing / បន្ទាប់ពីផ្សព្វផ្សាយ

1. Test installation: `composer require leap-lyhour/lighthouse-graphql-sanctum-auth`
2. Update documentation if needed / ធ្វើបច្ចុប្បន្នភាព documentation ប្រសិនបើត្រូវការ
3. Monitor issues and pull requests / តាមដាន issues និង pull requests

## Troubleshooting / ការដោះស្រាយបញ្ហា

### Package not found / Package មិនត្រូវបានរកឃើញ

- Ensure repository is public / ធានាថា repository ជា public
- Check repository URL is correct / ពិនិត្យមើល repository URL ត្រឹមត្រូវ
- Wait a few minutes for Packagist to index / រង់ចាំមួយរយៈពេលខ្លីសម្រាប់ Packagist index

### Auto-update not working / Auto-update មិនដំណើរការ

- Check GitHub token is valid / ពិនិត្យមើល GitHub token ត្រឹមត្រូវ
- Ensure webhook is configured / ធានាថា webhook ត្រូវបានកំណត់
- Manually trigger update on Packagist / ដំណើរការ update ដោយដៃនៅ Packagist

