#!/bin/bash

connectionKey=$(<secrets/connectionKey)
deployKey=$(<secrets/deployKey)
url=$(<secrets/url)
username=$(<secrets/username)

command="""
ssh-add .ssh/github_skokiBD
cd ~/public_html/skokiBD
git pull origin deploy
"""

ssh -i $connectionKey $username@$url $command
