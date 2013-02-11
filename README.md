FoodServer
==========

Desc

## Pushing to production

Add the production environment as a remote repository

    git remote add prod ssh://SSHALIAS/home/srv/vhost/snabbacash.fik1.net/foodserver.git

Push to both github for collaboration and the production environment for
instant updates.

    git push origin
    git push prod

## Fetching new submodules on production

Run `make update-submodules` on the production environment.

Or here is what it does step by step:

    $ cd ~/foodserver # Go to the checked out repo (non-bare)

    # Set the git dir to the bare-repository, the working tree to the
    # non-bare and then update, placing the files in the non-bare.

    $ GIT_DIR=~/foodserver.git GIT_WORKING_TREE=~/foodserver git submodule init
    $ GIT_DIR=~/foodserver.git GIT_WORKING_TREE=~/foodserver git submodule update
