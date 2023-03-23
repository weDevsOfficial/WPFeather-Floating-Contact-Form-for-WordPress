#!/usr/bin/env bash

# Exit if any command fails.
set -e

# Change to the expected directory.
cd "$(dirname "$0")"
cd ..
DIR=$(pwd)
PROJECT_NAME="wpfeather"
BUILD_DIR="$DIR/build/$PROJECT_NAME"

# Enable nicer messaging for build status.
BLUE_BOLD='\033[1;34m';
GREEN_BOLD='\033[1;32m';
RED_BOLD='\033[1;31m';
YELLOW_BOLD='\033[1;33m';
COLOR_RESET='\033[0m';
error () {
    echo -e "\n${RED_BOLD}$1${COLOR_RESET}\n"
}
status () {
    echo -e "\n${BLUE_BOLD}$1${COLOR_RESET}\n"
}
success () {
    echo -e "\n${GREEN_BOLD}$1${COLOR_RESET}\n"
}
warning () {
    echo -e "\n${YELLOW_BOLD}$1${COLOR_RESET}\n"
}

status "Time to build the ZIP file"

# remove the build directory if exists and create one
rm -rf "$DIR/build/$PROJECT_NAME"
mkdir -p "$BUILD_DIR"

# Run the build.
# status "Installing dependencies... 📦"
# npm install

status "Generating build..."
npm run build

# Copy all files
status "Copying files... ✌️"
FILES=(wpfeather.php assets includes composer.json composer.lock)

for file in ${FILES[@]}; do
  cp -R $file $BUILD_DIR
done

# Delete the source JS directory
# rm -rf "$BUILD_DIR/assets/src"
# rm -rf "$BUILD_DIR/assets/less"

# Install composer dependencies
status "Installing dependencies... 📦"
cd $BUILD_DIR
composer install --optimize-autoloader --no-dev -q

# Remove composer files
rm composer.json composer.lock

# go one up, to the build dir
status "Creating archive... 🎁"
cd ..
zip -r -q $PROJECT_NAME.zip $PROJECT_NAME

# remove the source directory
rm -rf $PROJECT_NAME

success "Done. You've built $PROJECT_NAME! 🎉 "
