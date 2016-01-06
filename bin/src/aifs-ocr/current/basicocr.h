#ifdef _CH_
#pragma package <opencv>
#endif

#ifndef _EiC
#include <cv.h>
#include <highgui.h>
#include <ml.h>
#include <stdio.h>
#include <ctype.h>
#endif

class basicOCR{
public:
basicOCR ();
private:
char file_path[255];
int train_samples;
int classes;
CvMat* trainData;
CvMat* trainClasses;
int size;
static const int K=10;
CvKNearest *knn;
void getData();
};
