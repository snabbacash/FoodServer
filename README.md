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
