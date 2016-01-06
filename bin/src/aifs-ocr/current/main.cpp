#ifdef _CH_
#pragma package <opencv>
#endif

#ifndef _EiC
#include <cv.h>
#include <highgui.h>
#include <stdio.h>
#include <ctype.h>
#include "basicocr.h"
#endif

IplImage* imagen;
int red,green,blue;
IplImage* screenBuffer;
int drawing;
int r,last_x, last_y;


int main( int argc, char** argv )
{
    printf( "Basic OCR by David Millan Escriva | Damiles\n"
        "\tESC - quit the program\n");
	drawing=0;
	r=10;
	red=green=blue=0;
	last_x=last_y=red=green=blue=0;
	//Create image
	//imagen=cvCreateImage(cvSize(128,128),IPL_DEPTH_8U,1);
	//Set data of image to white
	///cvSet(imagen, CV_RGB(255,255,255),NULL);
	//Image we show user with cursor and other artefacts we need
	//screenBuffer=cvCloneImage(imagen);

	//Create window
     	//cvNamedWindow( "Demo", 0 );

	//cvResizeWindow("Demo", 128,128);
	//Create mouse CallBack
	//cvSetMouseCallback("Demo",&on_mouse, 0 );


	//////////////////
	//My OCR
	//////////////////
basicOCR ocr;

}






