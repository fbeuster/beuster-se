#!/bin/bash

GREEN='\033[1;32m'
INSTALL_PATH=$(pwd)
NC='\033[0m'
RED='\033[1;31m'
THEMES=true

while [[ $# -gt 0 ]]
do
  key="$1"

  case $key in
    -t|--no-themes)
      # skip building themes
      THEMES=false
      ;;
    *)
      echo $key
      # unknown option
      ;;
  esac
  shift
done

echo -e "start build asset files...\n"

# check dependencies
echo -n "checking dependencies..."
hash sass 2>/dev/null || { echo -e >&2 " ${RED}fail${NC}"; echo "Missing dependency sass. Aborting."; exit 1; }
hash js-build 2>/dev/null || { echo -e >&2 " ${RED}fail:${NC}"; echo "Missing dependency js-build. Aborting."; exit 1; }
echo -e " ${GREEN}ok${NC}"

# check location
echo -n "checking location..."
if [ ! -f "README.md" ]; then
  echo -e " ${RED}fail${NC}"
  echo "Not in project root directory. Aborting."
  exit 1
fi
echo -e " ${GREEN}ok${NC}"

# build system css
echo -n "building system css..."
cd system/assets/css/admin
sass styles.scss styles.css
cd $INSTALL_PATH
echo -e " ${GREEN}done${NC}"

# build system js
echo -n "building system js..."
cd system/assets/js/admin
js-build 1>/dev/null application.js admin.js
cd $INSTALL_PATH
echo -e " ${GREEN}done${NC}"


if [[ $THEMES == false ]]; then
  echo "skip building themes"

else
  # build theme beuster-se-2013 css
  echo -n "building theme beuster-se-2013 css..."
  cd theme/beuster-se-2013/styles
  sass application.scss application.css
  cd $INSTALL_PATH
  echo -e " ${GREEN}done${NC}"

  # build theme beuster-se-2013 js
  echo -n "building theme beuster-se-2013 js..."
  cd theme/beuster-se-2013/scripts
  js-build 1>/dev/null application.js beusterse.js
  cd $INSTALL_PATH
  echo -e " ${GREEN}done${NC}"

  # build theme beuster-se-2016 css
  echo -n "building theme beuster-se-2016 css..."
  cd theme/beuster-se-2016/assets/css
  sass styles.scss styles.css
  cd $INSTALL_PATH
  echo -e " ${GREEN}done${NC}"

  # build theme beuster-se-2016 js
  echo -n "building theme beuster-se-2016 js..."
  cd theme/beuster-se-2016/assets/js
  js-build 1>/dev/null application.js beusterse.js
  cd $INSTALL_PATH
  echo -e " ${GREEN}done${NC}"
fi

echo -e "\ndone build asset files."