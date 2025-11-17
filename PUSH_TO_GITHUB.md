# Push to GitHub Guide / មគ្គុទេសក៍ Push ទៅ GitHub

Quick guide to push this package to GitHub.
មគ្គុទេសក៍រហ័សសម្រាប់ push package នេះទៅ GitHub។

## Repository URL / Repository URL

**GitHub Repository:** `https://github.com/lyhourdev/lighthouse-graphql-sanctum-auth`

## Commands / Commands

### Option 1: Push Existing Repository (Recommended) / ជម្រើស ១: Push Repository ដែលមាន (បានណែនាំ)

Since you already have the code, use these commands:
ដោយសារអ្នកមាន code រួចហើយ, ប្រើ commands ទាំងនេះ:

```bash
cd packages/leap-lyhour/lighthouse-graphql-sanctum-auth

# Initialize git repository
# Initialize git repository
git init

# Add all files
# បន្ថែម files ទាំងអស់
git add .

# Create initial commit
# បង្កើត commit ដំបូង
git commit -m "Initial commit: Enterprise Lighthouse GraphQL Sanctum Auth package"

# Set main branch
# កំណត់ main branch
git branch -M main

# Add remote repository (SSH)
# បន្ថែម remote repository (SSH)
git remote add origin git@github.com:lyhourdev/lighthouse-graphql-sanctum-auth.git

# Or use HTTPS if you prefer
# ឬប្រើ HTTPS ប្រសិនបើអ្នកចូលចិត្ត
# git remote add origin https://github.com/lyhourdev/lighthouse-graphql-sanctum-auth.git

# Push to GitHub
# Push ទៅ GitHub
git push -u origin main
```

### Option 2: Using HTTPS / ជម្រើស ២: ប្រើ HTTPS

If you prefer HTTPS instead of SSH:
ប្រសិនបើអ្នកចូលចិត្ត HTTPS ជំនួសឱ្យ SSH:

```bash
cd packages/leap-lyhour/lighthouse-graphql-sanctum-auth

git init
git add .
git commit -m "Initial commit: Enterprise Lighthouse GraphQL Sanctum Auth package"
git branch -M main
git remote add origin https://github.com/lyhourdev/lighthouse-graphql-sanctum-auth.git
git push -u origin main
```

## After Pushing / បន្ទាប់ពី Push

### 1. Create Release Tag / បង្កើត Release Tag

```bash
# Create version tag
# បង្កើត version tag
git tag -a v1.0.0 -m "Initial release v1.0.0"

# Push tag to GitHub
# Push tag ទៅ GitHub
git push origin v1.0.0
```

### 2. Verify on GitHub / ផ្ទៀងផ្ទាត់នៅ GitHub

1. Go to: `https://github.com/lyhourdev/lighthouse-graphql-sanctum-auth`
2. Verify all files are present / ផ្ទៀងផ្ទាត់ថា files ទាំងអស់មាន
3. Check README.md displays correctly / ពិនិត្យមើល README.md បង្ហាញត្រឹមត្រូវ

### 3. Submit to Packagist / Submit ទៅ Packagist

After pushing to GitHub:
បន្ទាប់ពី push ទៅ GitHub:

1. Go to: https://packagist.org/packages/submit
2. Enter: `https://github.com/lyhourdev/lighthouse-graphql-sanctum-auth`
3. Click "Check" and then "Submit"

## Troubleshooting / ការដោះស្រាយបញ្ហា

### Authentication Error / Authentication Error

If you get authentication errors:
ប្រសិនបើអ្នកទទួល authentication errors:

**For SSH:**
```bash
# Check SSH key is added to GitHub
# ពិនិត្យមើល SSH key ត្រូវបានបន្ថែមទៅ GitHub
ssh -T git@github.com
```

**For HTTPS:**
```bash
# Use personal access token
# ប្រើ personal access token
# GitHub will prompt for username and token
```

### Files Not Showing / Files មិនបង្ហាញ

```bash
# Make sure .gitignore is not excluding important files
# ធានាថា .gitignore មិនលុប files សំខាន់ៗ
git status

# Check what will be committed
# ពិនិត្យមើលអ្វីដែលនឹងត្រូវ commit
git add -n .
```

## Quick Copy-Paste Commands / Commands រហ័ស Copy-Paste

```bash
cd packages/leap-lyhour/lighthouse-graphql-sanctum-auth && \
git init && \
git add . && \
git commit -m "Initial commit: Enterprise Lighthouse GraphQL Sanctum Auth package" && \
git branch -M main && \
git remote add origin git@github.com:lyhourdev/lighthouse-graphql-sanctum-auth.git && \
git push -u origin main
```

