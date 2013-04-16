#!/bin/sh
FILES_MASK=*
#FORBIDDEN_CODECS="mpeg4 h264"
FORBIDDEN_CODECS="mpeg4"

die () {
    echo >&2 "$@"
    exit 1
}

isConvertRequired () {
    file=$1
    fileInfo=$(ffmpeg -i $file 2>/dev/stdout)
    for forbidden_codec in $FORBIDDEN_CODECS
    do
        flag=$(test "${fileInfo#*$forbidden_codec}" != "$fileInfo" && echo "1")
        if [ "$flag" != "" ]; then
           return "1";
        fi
    done
    return "0"
}

convert () {
    fileIn=$1
    ext=${fileIn##*.}
    fileOut="$fileIn.tmp.$ext"
    rm $fileOut -f
    trap 'rm $fileOut -f; exit 0' INT
    ffmpeg -loglevel panic -i $fileIn -vcodec libx264 $fileOut 2>/dev/null
    if [ -f "$fileOut" ]
        then 
            mv $fileOut $fileIn -f
            echo "success."
        else 
            echo "failed."
    fi
}

me=`basename $0`
[ "$#" -eq 1 ] || die "usage: $me <path to directory>"
SCAN_DIR=$1

echo "Processing directiry '$SCAN_DIR':"
for f in $SCAN_DIR/$FILES_MASK
do
    #echo "checking file - $f"
    isConvertRequired $f
    res=$?
    if [ "$res" -eq "1" ]; then
        echo "converting file '$f', please wait..."
        convert $f
    fi
done
