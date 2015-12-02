#/bin/bash

ROOT_DIR="/data_bingo/Babel"

ARCHIVE_NAME="archive"

for path in ${ROOT_DIR}/boxlen*; do	
	echo ${path}
	pathname=$(basename "${path}")
	for output in ${path}/output*; do
		outputname=$(basename "${output}")
		archivepath=${ARCHIVE_NAME}/${pathname}/${outputname}
		echo "mkdir -p ${archivepath}"
		mkdir -p ${archivepath}
		echo ${output} > ${archivepath}/ls.txt
		ls -l ${output} >> ${archivepath}/ls.txt
		cp ${output}/info*.txt ${archivepath}/		
	done
done
