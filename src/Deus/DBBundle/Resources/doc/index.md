DEUS DATABASE
=============

Global architecture
-------------------

The system is composed of a common database used by two separate web applications:
 - deus-db: application to search/edit/import data, only available from the obspm network
 - deus-library: public application to search/download data, available from the internet

![Global Architecture](schema.png)

Interface Usage
---------------

### DEUS-DB Search

This interface allows the user to search for objects and get informations about it.
It also allows to simplifly access to edition and publication.

#### Simple search usage

#### Link to edition

#### Publication

### DEUS-DB Admin

This interface allows the user to edit settings and the datas.

### DEUS-Library

This is the public interface for visitors. They can select simulations then objects and download them directly

New Simulation/File integration
-------------------------------
Integrating new data is a 2 step procedure:
 1. gather data into an index directory from the server using a bash script makeDeusIndex.sh
 2. parse the index using rules in the PHP application to import data into the database

### Make index with bash

``
    bin/makeDeusIndex.sh
``

### php import

``
    app/console deusdb:import_index <path> [ruleset]
``


### edit import rules

The rules files is located into app/config/


(Re)Installation
----------------
### Prerequires

### deus-db Installation

### deus-library Installation

### Roxxor2/ installation


