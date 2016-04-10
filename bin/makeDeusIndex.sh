#!/bin/bash

####################
# CONFIG           #
# Please edit here #
####################

# DIRECTORY TO FIND THE FILES, CAN USE WILDCARD
#   example: "/data_bingo/Babel/boxlen*"
ROOT_DIR=${1} #"/efiler1/Babel_le/boxlen*"

# DIRECTORY FOR THE INDEX (DEFAULT: USE STORAGE NAME ON LOCAL PATH)
INDEX_DIR=${2} #./data/${STORAGE}

# STORAGE ID IN THE DATABASE, CURRENT VALUES ARE:
#   "meudon_bingo_data": /data_bingo/ in Meudon
#   "meudon_efiler_data1": /efiler1/ in Meudon
#   "meudon_efiler_data2": /efiler2/ in Meudon
#   "meudon_asisu_deus_data": /asisu/deus_data/ in Meudon
#   "idris_ergon_storedir": $STOREDIR on Ergon / IDRIS
#   "tgcc_curie_storedir": $STOREDIR on Curie / TGCC
# More can be added directly on the database   
STORAGE=${3}  #"meudon_efiler_data1" 



################################
# SCRIPT PART                  #
# You don't have to touch here #
################################

# INDEX_DIR function
index_dir()
{
    local subpath
    local indexpath
    echo " adding ${2}"
    echo ${1} > ${2}/ls.txt
    ls -lp ${1} | grep -v / >> ${2}/ls.txt
	for subpath in ${1}/*; do 	
        if [ -d ${subpath} ]; then		
	        subname=$(basename "${subpath}")
	        indexpath=${2}/${subname}
	        mkdir -p ${indexpath}
	        index_dir ${subpath} ${indexpath}
	    fi
	    if [[ -e ${subpath} && $(basename "${subpath}") == info*.txt ]]; then
            cp ${subpath} ${2}/
        fi
	done
}

index_simu()
{
    # CREATE SIMULATION DIR
	echo "SIMULATION" ${path}
	pathname=$(basename "${path}")
	mkdir -p ${2}/${pathname}
	echo -e "ROOT_DIR=${1}\nSTORAGE=${STORAGE}\n" >  ${2}/${pathname}/index.txt
	index_dir ${path} ${2}/${pathname}
}


echo -e "Index ${ROOT_DIR} into ${INDEX_DIR}"
for path in ${ROOT_DIR}; do 
    index_simu ${path} ${INDEX_DIR}
done



