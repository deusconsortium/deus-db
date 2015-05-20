#/bin/bash

php app/console sbo:generate:crud -n --entity=DeusDBBundle:Boxlen --with-write --overwrite
php app/console sbo:generate:crud -n --entity=DeusDBBundle:Cosmology --with-write --overwrite
php app/console sbo:generate:crud -n --entity=DeusDBBundle:Resolution --with-write --overwrite
php app/console sbo:generate:crud -n --entity=DeusDBBundle:Location --with-write --overwrite
php app/console sbo:generate:crud -n --entity=DeusDBBundle:Storage --with-write --overwrite
php app/console sbo:generate:crud -n --entity=DeusDBBundle:ObjectGroup --with-write --overwrite
php app/console sbo:generate:crud -n --entity=DeusDBBundle:GeometryType --with-write --overwrite
php app/console sbo:generate:crud -n --entity=DeusDBBundle:ObjectFormat --with-write --overwrite
php app/console sbo:generate:crud -n --entity=DeusDBBundle:ObjectType --with-write --overwrite
php app/console sbo:generate:crud -n --entity=DeusDBBundle:Geometry --with-write --overwrite
php app/console sbo:generate:crud -n --entity=DeusDBBundle:ObjectType --with-write --overwrite
php app/console sbo:generate:crud -n --entity=DeusDBBundle:Simulation --with-write --overwrite
php app/console sbo:generate:crud -n --entity=DeusDBBundle:User --with-write --overwrite