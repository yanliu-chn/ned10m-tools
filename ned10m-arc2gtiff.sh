#!/bin/bash
# convert USGS NED 10m arcgrid raw raster to geotiff using gdal
# Author: Yan Y. Liu <yanliu@illinois.edu>
# Date: 02/10/2016
gridzip=$1
[ ! -f $gridzip ] && echo "ERROR: no arcgrid zip input $gridzip." && echo "Usage: $0 input_zip output_file" && exit 1
gridzip=`readlink -f $gridzip`
outfile=$2
[ -z $outfile ] && echo "ERROR: pls specify output geotiff file." && echo "Usage: $0 input_zip output_file" && exit 1

tmpdir=/tmp/arcgrid2geotiff
[ ! -d $tmpdir ] && mkdir $tmpdir
cdir=`pwd`

fn=`basename $gridzip .zip`
cd $tmpdir
[ -d $fn ] && rm -fr $fn
unzip $gridzip

subdir=`ls -d $fn/*$fn*/`
[ ! -d $subdir ] && echo "ERROR: no arcgrid data dir in $fn." && exit 1

cd $cdir
gdal_translate -of GTiff -co "TILED=YES" -co "COMPRESS=LZW" $tmpdir/$subdir $outfile

rm -fr $tmpdir/$fn
