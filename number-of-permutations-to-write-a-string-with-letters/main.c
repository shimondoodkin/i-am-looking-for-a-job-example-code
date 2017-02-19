/*
Task:

 
 
Filter Question
 
 
You are given a cypher that translates each letter to a number:
a=1, b=2, c=3...z=26
 
Your job is to calculate the number of permutations to translate a given number to letters.
 
For example the number 13 can be translated into 2 different translation:
Either "a=1, c=3" or "m=13".
 
You should output only the number of permutations to a new line.
 
The code should be written in C and and compile cleanly under gcc .
 
 
 
 
Attempt 1:


#include <stdio.h>
void main(void)
{
 int n=0;
 printf("enter a number between 1 to 26: ");
 scanf("%u",&n);
 if(n<1 || n>26 ) {
  printf("number not in range between 1 to 26");
  return;
 }
 if(n>10 ) {
  printf("2");
 }
 else
  printf("1");
}
 


but, than she said:

Hi Simon,
About your solution:
 
-The input number for this question isn’t limited to a number between 1 and 26. It is actually not limited. For example: You can get: 11244343, or 233333333333333334445.
You can assume that the input number is limited to N digits. N can be a number that you decide upon but should be at least 128
 
Please try to write a new solution,
 
Thank you





Attempt 2:
*/

#include <stdio.h>
#include <string.h>


void recoursive_one_or_two_fit(char *string_of_numbers , int string_of_numbers_length, int current_position, int *counter)
{
   char current_number;
   char next_number;
   int next_position=current_position+1;
   if(next_position < string_of_numbers_length)
   {
	   next_number=string_of_numbers[next_position]-'0';
	   current_number=string_of_numbers[current_position]-'0';
	   if(current_number<=1 /* and next number is any digit */
	   || current_number==2 && next_number<=6 ) {
	        //printf("A");
			recoursive_one_or_two_fit( string_of_numbers ,  string_of_numbers_length, current_position+2 , counter);
	   }
	   //also take the 1 digit route
	   //printf("B");
	   recoursive_one_or_two_fit( string_of_numbers ,  string_of_numbers_length, current_position+1 , counter);
   }
   else
   if(current_position < string_of_numbers_length)
   {
	   //printf("C");
	   //current_number=string_of_numbers[current_position]-'0';
	   //if(current_number is any digit)
	   recoursive_one_or_two_fit( string_of_numbers ,  string_of_numbers_length, current_position+1 , counter);
   }
   else if(current_position == string_of_numbers_length)
   {
	   //if finished, report string finished.
	   
	   //printf("D");
	   (*counter)++;
   }
}

void main(void)
{
 char string_of_numbers[129];
 int string_of_numbers_strlen;
 int i;
 int counter=0;
 
 printf("enter a number: ");
 scanf("%128s",string_of_numbers);
 string_of_numbers_strlen=strlen(string_of_numbers);
 //printf("number s %s\n",string_of_numbers);
 //printf("number len %d\n",string_of_numbers_strlen);
 for(i=0;i<string_of_numbers_strlen;i++)
 {
	if(string_of_numbers[i]<'0' || string_of_numbers[i]>'9') {
		 printf("please enter only numbers\n");		
		 return;
	}
 }
 recoursive_one_or_two_fit(string_of_numbers, string_of_numbers_strlen , 0, &counter);
 printf("number of permutations is %d\n", counter );
}

