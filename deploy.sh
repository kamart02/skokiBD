#!/bin/bash

connectionKey=$(<secrets/connectionKey)
deployKey=$(<secrets/deployKey)
url=$(<secrets/url)
username=$(<secrets/username)

command="""
cd ~/public_html/skokiBD;
git pull origin deploy
"""

echo "$command"

ssh -i $connectionKey $username@$url $command
