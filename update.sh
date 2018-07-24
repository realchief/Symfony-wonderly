#!/bin/sh

APACHEUSER=`ps aux | grep -E '[a]pache|[h]ttpd|nginx' | grep -v root | head -1 | cut -d\  -f1`
VAR_PATH="`pwd`/var"
UPLOAD_PATH="`pwd`/web/uploads"

#
# Check permissions
#
getfacl $VAR_PATH | grep "$APACHEUSER" | grep -v "#" 2>&1 > /dev/null

if [ "$?" = "1" ]; then
  printf "%-120s %s\n" "Set permission by setfacl."

  # Permissions not set yet.
  setfacl -R -m u:$APACHEUSER:rwX -m u:`whoami`:rwX $VAR_PATH $UPLOAD_PATH
  setfacl -dR -m u:$APACHEUSER:rwX -m u:`whoami`:rwX $VAR_PATH $UPLOAD_PATH
  if [ $(getent group phpteam ) ]; then
    setfacl -R -m g:phpteam:rwX var $VAR_PATH $UPLOAD_PATH
    setfacl -dR -m g:phpteam:rwX var $VAR_PATH $UPLOAD_PATH
  fi
else
  printf "%-120s %s\n" "Permission already set."
fi
