/**
 * validates that a matrix is without separated islands
 * 
 * the general idea is to take first shape it meets and iterate it fully in all directions until end, then see if anything is left.
 *
 * this version iterates in all directions and marks off places visited, then counts what is left
 *
 */
// compiled with g++ -std=c++14 isvalid.cc

#include <iostream>
#include<array>
#include<vector>
#include<algorithm>

 using namespace std;
 

 
 array<array<int,7>,7> test1 =
 {{
 {0,1,1,0,0,0,0},
 {0,1,1,0,0,0,0},
 {0,0,0,0,0,0,0},
 {0,0,0,0,0,0,0},
 {0,0,0,0,0,0,0},
 {0,0,0,0,0,0,0},
 {0,0,0,0,0,0,0}
 }};
 
 array<array<int, 7>, 7> test2 =
 {{
 {2,1,1,0,0,0,0},
 {2,1,1,0,0,0,0},
 {2,0,0,0,0,0,0},
 {2,2,0,0,1,1,0},
 {0,0,0,0,1,1,0},
 {0,0,0,0,0,0,0},
 {0,0,0,0,0,0,0}
 }};
 
 
 array<array<int, 7>, 7> test3 =
 {{
 {0,0,0,0,0,0,0},
 {0,1,0,1,1,1,0},
 {0,1,0,1,0,1,0},
 {0,1,0,1,0,1,0},
 {0,1,0,0,0,1,0},
 {0,1,1,1,1,1,0},
 {0,0,0,0,0,0,0}
 }};
 
 array<array<int, 7>, 7> test4 =
 {{
 {0,0,0,0,0,0,0},
 {0,0,0,1,1,1,0},
 {0,0,0,1,0,1,0},
 {0,1,1,1,1,1,0},
 {0,0,0,1,0,1,0},
 {0,0,0,1,1,1,0},
 {0,0,0,0,1,0,0}
 }};
 
 array<array<int, 7>, 7> test5 =
 {{
 {0,0,0,0,0,0,0},
 {0,1,0,0,0,0,0},
 {0,0,0,0,0,0,0},
 {0,0,0,0,0,0,0},
 {0,0,0,0,0,0,0},
 {0,0,0,0,0,0,0},
 {0,0,0,0,0,0,0}
 }};
 
 array<array<int, 7>, 7> mark_found =
 {{
 {0,0,0,0,0,0,0},
 {0,0,0,0,0,0,0},
 {0,0,0,0,0,0,0},
 {0,0,0,0,0,0,0},
 {0,0,0,0,0,0,0},
 {0,0,0,0,0,0,0},
 {0,0,0,0,0,0,0}
 }};
 
 
 // some local defines
 #define none 0
 #define up 1
 #define down 2
 #define left 3
 #define right 4
 
 void eat_a_single_chocochip_pattern_from_a_cookie(auto n,auto x,auto y,auto from_direction,auto &&m,auto &&mark_found,auto mx,auto my)
 {
	mark_found[y][x]=n; // mark off the used value. used (mark_found) variable to be non destructive on inputs
	
	// if direction is not backwards,  
	//                                 and there is a value equals to n that we look for,
	//                                                  and it not used already
	//                                                                       search deeper from this value
	if(from_direction!=up   && y-1>0  && m[y-1][x]==n && mark_found[y-1][x]!=n)eat_a_single_chocochip_pattern_from_a_cookie(n,x,y-1,down  ,m,mark_found,mx,my);
	if(from_direction!=down && y+1<my && m[y+1][x]==n && mark_found[y+1][x]!=n)eat_a_single_chocochip_pattern_from_a_cookie(n,x,y+1,up    ,m,mark_found,mx,my);
	if(from_direction!=left && x-1>0  && m[y][x-1]==n && mark_found[y][x-1]!=n)eat_a_single_chocochip_pattern_from_a_cookie(n,x-1,y,right ,m,mark_found,mx,my);
	if(from_direction!=right&& x+1<mx && m[y][x+1]==n && mark_found[y][x+1]!=n)eat_a_single_chocochip_pattern_from_a_cookie(n,x+1,y,left  ,m,mark_found,mx,my);
 }
 
 bool check_is_anything_left(auto n,auto &&m,auto &&mark_found,auto mx,auto my)
 {
	for(size_t y=0;y<my;y++)
	{
		for(size_t x=0;x<mx;x++)
		{
			if(m[y][x]==n && mark_found[y][x]!=n)
				return true;
		}
	}
	return false;
 }
 
 bool clear(auto &&m)
 {
    size_t mx=m[0].size(),my=m.size(); 
	for(size_t y=0;y<my;y++)
	{
		for(size_t x=0;x<mx;x++)
		{
			m[y][x]=0;
		}
	}
 }
  
 bool find_number_and_check(auto &&m,auto &&mark_found) // all allocations are external intentionally
 {
    clear(mark_found);
	vector<int> numbersdone;
    size_t mx=m[0].size(),my=m.size(); 
	for(size_t y=0;y<my;y++)
	{
	    auto &row=m[y];
		for(size_t x=0;x<mx;x++)
		{
			auto &n=row[x];
			if ( n!=0 && find(numbersdone.begin(), numbersdone.end(), n) == numbersdone.end() )
			{
				numbersdone.push_back(n);
				eat_a_single_chocochip_pattern_from_a_cookie(n,x,y,none,m,mark_found,mx,my);
				auto is_anything_left=check_is_anything_left(n,m,mark_found,mx,my);
				cout <<n<<(is_anything_left?"something left":"is OK")<<endl;
				if(is_anything_left)
					return false;
			}
		}
	}
	return true;
 }
  
 bool print(auto name,auto &&m)
 {
	cout<<endl;
	cout<<name<<":"<<endl;
	
    size_t mx=m[0].size(),my=m.size(); 
	for(size_t y=0;y<my;y++)
	{
	    auto &row=m[y];
		for(size_t x=0;x<mx;x++)
		{
		   auto &n=row[x];
		   cout <<n;
		   if(x<mx-1)cout<<",";
		}
		cout<<endl;
	}
 }
 
 
int main (void)
{
 print("test2",test2);
 if(find_number_and_check(test2,mark_found))
  cout <<" true"<<endl;
 else
  cout <<" false"<<endl;
  print("mark_found",mark_found);
 return 0;
}


 /*
 output
 
$ a.exe

test5:
0,0,0,0,0,0,0
0,1,0,0,0,0,0
0,0,0,0,0,0,0
0,0,0,0,0,0,0
0,0,0,0,0,0,0
0,0,0,0,0,0,0
0,0,0,0,0,0,0
1is OK
 true

mark_found:
0,0,0,0,0,0,0
0,1,0,0,0,0,0
0,0,0,0,0,0,0
0,0,0,0,0,0,0
0,0,0,0,0,0,0
0,0,0,0,0,0,0
0,0,0,0,0,0,0


$ a.exe

test3:
0,0,0,0,0,0,0
0,1,0,1,1,1,0
0,1,0,1,0,1,0
0,1,0,1,0,1,0
0,1,0,0,0,1,0
0,1,1,1,1,1,0
0,0,0,0,0,0,0
1is OK
 true

mark_found:
0,0,0,0,0,0,0
0,1,0,1,1,1,0
0,1,0,1,0,1,0
0,1,0,1,0,1,0
0,1,0,0,0,1,0
0,1,1,1,1,1,0
0,0,0,0,0,0,0

$ a.exe

test2:
2,1,1,0,0,0,0
2,1,1,0,0,0,0
2,0,0,0,0,0,0
2,2,0,0,1,1,0
0,0,0,0,1,1,0
0,0,0,0,0,0,0
0,0,0,0,0,0,0
2is OK
1something left
 false

mark_found:
2,1,1,0,0,0,0
2,1,1,0,0,0,0
2,0,0,0,0,0,0
2,2,0,0,0,0,0
0,0,0,0,0,0,0
0,0,0,0,0,0,0
0,0,0,0,0,0,0

 */