#/bin/bash

ARCHIVE_DIR="/home/jpasdeloup/data/archive/ergon"
for dir in ${ARCHIVE_DIR}/*; do
 php app/console deusdb:import:archive ${dir} idris_ergon_storedir ramses
done

ARCHIVE_DIR="/home/jpasdeloup/data/archive/curie"
for dir in ${ARCHIVE_DIR}/*; do
 php app/console deusdb:import:archive ${dir} tgcc_curie_storedir multi
done

