# StoryBlok Quickstart

A POC for storyblok

# Backend

### Installation

From the magento root dir :
`cd storyblok/`


`composer install`

### Running locally

Run the following commands in /storyblok folder.

```bash
# DEV
STORYBLOK_API_KEY=30zIXB7aFWUFst3fj1pQEgtt php  -d variables_order=EGPCS -S 127.0.0.1:8888

```

To get production/staging content (debugging purpose)

```bash
# STAGING
STORYBLOK_API_KEY=QCfvZZ9ovHSotpm9UD2N0Qtt php  -d variables_order=EGPCS -S 127.0.0.1:8888

# PROD
STORYBLOK_API_KEY=Op15qvjU7G5JtRZMz88glQtt php  -d variables_order=EGPCS -S 127.0.0.1:8888
```

# Frontend

### Installation

frontend preprocessing is handled by the parent magento `gulpfile`

```
cd ../
# follow the README.md process
```

### Running locally

```bash
gulp storyblok
```

## Custom layouts

Use query string  `layout` to access a specific template file as root template. This include use `_` as prefix :

`/?layout=styleguide` -> will includes `components/layouts/_styleguide.html`



# Storyblok schema synchronization

** THIS PROCESS IS AUTOMATED **

In order to migrate storyblok schema between env (dev/staging/production) we're using Storyblok CLI
https://github.com/storyblok/storyblok#content-migrations

To begin you need admin privileges on all these spaces :

- Dev space ID : `97892` https://app.storyblok.com/#!/me/spaces/97892/dashboard
- Staging space ID : `97909` https://app.storyblok.com/#!/me/spaces/97909/dashboard
- Production space ID : `96280` https://app.storyblok.com/#!/me/spaces/96280/dashboard


### Synchronization Process

After editing schemas on DEV :

0) Pull config from DEV env. `storyblok pull-components --space 97892`
0) Commit changes `git add components.97892.json presets.97892.json`
0) Push to STAGING env. `storyblok push-components components.97892.json --space 97909`
0) Push to PROD env. `storyblok push-components components.97892.json --space 96280`


### Contribution

/!\ You need to commit the `components.97892.json` each time you edit components or fields on the dev space.

# Troubleshooting

Please refer to [TROUBLESHOOTING.md](docs/TROUBLESHOOTING.md)

# Contribution

Please refer to [CONTRIBUTE.md](docs/CONTRIBUTE.md)
