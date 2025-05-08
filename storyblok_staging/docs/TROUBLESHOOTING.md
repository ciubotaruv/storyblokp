# TROUUBLESHOOTING

## gyp: No Xcode or CLT version detected!

If you get the following error: `gyp: No Xcode or CLT version detected!`, please follow the instructions here:
https://medium.com/flawless-app-stories/gyp-no-xcode-or-clt-version-detected-macos-catalina-anansewaa-38b536389e8d

```
xcode-select --print-path
sudo rm -r -f /Library/Developer/CommandLineTools
xcode-select --install
npm install -g gulp@4
```

## gulp build issue

`This is probably not a problem with npm. There is likely additional logging output above.`

Check your current node version, need to be >=12

then `rm -rf node_modules` and run `npm install` again 