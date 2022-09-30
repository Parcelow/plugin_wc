#!/usr/bin/env bash
echo  
echo  
echo 🚩 "INICIO"
echo  
echo  

echo ✅ Get the plugin slug from this git repository.
PLUGIN_SLUG="parcelow"
echo $PLUGIN_SLUG
echo =================X==X==X========================

echo ✅ Get the current release version
TAG=$(sed -e "s/refs\/tags\///g" <<< $GITHUB_REF)
echo $TAG
echo =================X==X==X========================

echo ✅ Get the SVN data from wp.org in a folder named svn
svn co --depth immediates "https://plugins.svn.wordpress.org/$PLUGIN_SLUG" ./svn --username $SVN_USERNAME --password $SVN_PASSWORD --non-interactive

echo =================X==X==X========================

echo 🧹 cleanup...
svn cleanup
echo =================X==X==X========================

echo ✅ svn update...
svn update --set-depth infinity ./svn/trunk --username $SVN_USERNAME --password $SVN_PASSWORD --non-interactive
svn update --set-depth infinity ./svn/assets --username $SVN_USERNAME --password $SVN_PASSWORD --non-interactive
svn update --set-depth infinity ./svn/tags/$TAG --username $SVN_USERNAME --password $SVN_PASSWORD --non-interactive
echo =================X==X==X========================

echo ✅ if not exist create folder
[[ -d ./svn/trunk/assets ]] && ls ./svn/trunk || sudo mkdir ./svn/trunk/assets
ls ./svn/trunk
echo =================X==X==X========================

echo 💾 Copy files from src to svn/trunk
sudo cp -R ./src/* ./svn/trunk
ls ./svn/trunk
echo =================X==X==X========================

echo 💾 Copy the images from assets to svn/assets
sudo cp -R ./assets/* ./svn/trunk/assets
ls ./svn/trunk/assets
echo =================X==X==X========================

echo 💾 Copy the images from assets to svn/assets
sudo cp -R ./assets/* ./svn/assets/
ls ./svn/assets/
echo =================X==X==X========================

echo ✅ Replace the version in these 2 files.
sudo sed -i -e "s/__STABLE_TAG__/$TAG/g" ./svn/trunk/readme.txt
sudo sed -i -e "s/__STABLE_TAG__/$TAG/g" "./svn/trunk/woo-$PLUGIN_SLUG.php"
ls ./svn/trunk/
echo =================X==X==X========================

echo ↔️ Switch to SVN directory
cd ./svn
pwd
echo =================X==X==X========================

echo ⚙️ Prepare the files for commit in SVN
svn cleanup
svn add --force trunk
svn add --force assets
echo =================X==X==X========================

echo 🆕 Create the version tag in svn
svn cleanup
svn cp trunk tags/$TAG
echo =================X==X==X========================

echo ⚙️ Prepare the tag for commit
svn cleanup
svn add --force tags
echo =================X==X==X========================

echo 🗃️ Commit files to wordpress.org.
svn cleanup
svn ci --message "Release $TAG" \
       --username $SVN_USERNAME \
       --password $SVN_PASSWORD \
       --non-interactive