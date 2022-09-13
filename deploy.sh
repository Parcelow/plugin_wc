#!/usr/bin/env bash
# https://zerowp.com/?p=55

echo  
echo  
echo ✅ "INICIO"
echo  
echo  

echo ✅ Get the plugin slug from this git repository.
PLUGIN_SLUG="parcelow"

echo ✅ Get the current release version
TAG=$(sed -e "s/refs\/tags\///g" <<< $GITHUB_REF)

echo ✅ Get the SVN data from wp.org in a folder named `svn`
svn co --depth immediates "https://plugins.svn.wordpress.org/$PLUGIN_SLUG" ./svn

echo ✅ svn update...
svn update --set-depth infinity ./svn/trunk
svn update --set-depth infinity ./svn/assets
svn update --set-depth infinity ./svn/tags/$TAG

echo ✅ if not exist create folder
[[ -f ./svn/trunk/assets ]] && mkdir ./svn/trunk/assets

echo ✅ Copy files from `src` to `svn/trunk`
cp -R ./src/* ./svn/trunk

echo ✅ Copy the images from `assets` to `svn/assets`
cp -R ./assets/* ./svn/trunk/assets

echo ✅ Copy the images from `assets` to `svn/assets`
cp -R ./assets/* ./svn/assets

echo ✅ 3. Switch to SVN directory
cd ./svn

echo ✅ Replace the version in these 2 files.
sed -i -e "s/__STABLE_TAG__/$TAG/g" trunk/readme.txt
sed -i -e "s/__STABLE_TAG__/$TAG/g" "trunk/woo-$PLUGIN_SLUG.php"

echo ✅ Prepare the files for commit in SVN
svn add --force trunk
svn add --force assets

echo ✅ Create the version tag in svn
svn cp trunk tags/$TAG

echo ✅ Prepare the tag for commit
svn add --force tags

echo ✅ Commit files to wordpress.org.
svn ci  --message "Release $TAG" \
        --username $SVN_USERNAME \
        --password $SVN_PASSWORD \
        --non-interactive

echo  
echo  
echo ✅ "FIM"
echo  
echo  
