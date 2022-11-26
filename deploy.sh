#!/bin/bash

connectionKey=$(<secrets/connectionKey)
deployKey=$(<secrets/deployKey)
url=$(<secrets/url)
username=$(<secrets/username)

command="""
eval \$(ssh-agent -s);
ssh-add $deployKey;
cd ~/public_html/skokiBD;
git reset --hard origin/deploy;
"""

echo "$command"

ssh -i $connectionKey $username@$url $command
