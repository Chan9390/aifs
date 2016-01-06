
/**
 * AIFS HUMINT OCR binary
 * Copyright (c) digitaloversight
 */


#ifdef _CH_
#pragma package <opencv>
#endif

#ifndef _EiC
#include "cv.h"
#include "highgui.h"
#include "ml.h"
#include <stdio.h>
#include <stdlib.h>
#include <ctype.h>
#endif

//#include "preprocessing.h"
#include "basicocr.h"


void basicOCR::getData()
{
    IplImage* src_image;
    IplImage prs_image;
    CvMat row,data;
    char file[255];
    int i,j;

    //sprintf(file,"img.jpg",file_path);
    src_image = cvLoadImage("/root/ocr/images/img.jpg");
    if(!src_image){
        printf("Error: Cant load image %s\n", file);
        exit(-1);
    }
    //process file
    //prs_image = preprocessing(src_image, size, size);
    //Set class label
    //cvGetRow(trainClasses, &row, i*train_samples + j);
    //cvSet(&row, cvRealScalar(i));
    //Set data
    //cvGetRow(trainData, &row, i*train_samples + j);
    //IplImage* img = cvCreateImage( cvSize( size, size ), IPL_DEPTH_32F, 1 );
    //convert 8 bits image to 32 float image
    //cvConvertScale(&prs_image, img, 0.0039215, 0);
    //cvGetSubRect(img, &data, cvRect(0,0, size,size));
    //CvMat row_header, *row1;
    //convert data matrix sizexsize to vecor
    //row1 = cvReshape( &data, &row_header, 0, 1 );
    //cvCopy(row1, &row, NULL);

}


basicOCR::basicOCR()
{

    sprintf(file_path , "./images/");
    train_samples = 50;
    classes= 10;
    size=40;

    //trainData = cvCreateMat(train_samples*classes, size*size, CV_32FC1);
    //trainClasses = cvCreateMat(train_samples*classes, 1, CV_32FC1);

    getData();

    //train
    //train();
    //Test
    //test();

    printf(" ---------------------------------------------------------------\n");
    printf("|\tClass\t|\tPrecision\t|\tAccuracy\t|\n");
    printf(" ---------------------------------------------------------------\n");


}

