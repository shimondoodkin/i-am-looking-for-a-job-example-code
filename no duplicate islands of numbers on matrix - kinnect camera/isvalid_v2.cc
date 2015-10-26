/**
 * validates that a matrix is without separated islands
 * the general idea is to take first shape it meets and iterate it fully in all directions until end, then see if anything is left.
 *
 * this version iterates over a copy of a vector and searches points in all directions. it removes used items from vector. if anything left in the vector then the matrix is invalid.
 *
 */
// gcc version 5.2.0 // compiled with: g++ -std=c++14 isvalid.cc


#include <iostream>
#include<array>
#include<vector>
#include<algorithm>
#include<map>
#include<utility>

 using namespace std;


 class MatrixValidator
 {
 private:
 
 enum DirectionOfSearch { none,up,down,left,right };
  
 /**
  * this function remove all that comes in contact from giver vector
  *
  *  auto  &v,
  *  @param vector<pair<int,int>>::iterator prev - removes this from the given vector because it is used up;
  *  @param from_direction - direction no need to follow to not go backward.
  */
 static void eat_a_single_chocochip_pattern_from_a_cookie(auto  &v,auto const &prev,DirectionOfSearch from_direction)
 {
    auto y=prev->first;
	auto x=prev->second;
	v.erase(prev);
    if(from_direction!=up)
	{
	  auto cy=y-1,cx=x;
	  if(cy>0)
	  {
		  auto p=find_if(v.begin(), v.end(), [&] (auto p) { return p.first == cy && p.second == cx; } );
		  if(  p != v.end() ) // if any near by p found then mark it, (its already of same kind - no need to check)
		  {
			eat_a_single_chocochip_pattern_from_a_cookie(v,p,down);
		  }
	  }
	}
	
	
	if(from_direction!=down)
	{
	  auto cy=y+1,cx=x;
	  auto p=find_if(v.begin(), v.end(), [&] (auto p) { return p.first == cy && p.second == cx; } );
	  if(  p != v.end() ) 
	  {
	    eat_a_single_chocochip_pattern_from_a_cookie(v,p,up);
	  }
	}
	
	if(from_direction!=left) 
	{
	  auto cy=y,cx=x-1;
	  if(cx>0)
	  {
		  auto p=find_if(v.begin(), v.end(), [&] (auto p) { return p.first == cy && p.second == cx; } );
		  if(  p != v.end() ) 
		  {
			eat_a_single_chocochip_pattern_from_a_cookie(v,p,right);
		  }
	  }
	}
	
	if(from_direction!=right)
	{
	  auto cy=y,cx=x+1;
	  auto p=find_if(v.begin(), v.end(), [&] (auto p) { return p.first == cy && p.second == cx; } );
	  if(  p != v.end() ) 
	  {
	    eat_a_single_chocochip_pattern_from_a_cookie(v,p,left);
	  }
	}
 }
 
 public:
 /*
  * checks if a map is valid. it has no duplicate islands of same type.
  *
  * @param  map<auto, vector< pair<size_t,size_t> > > m, vector is of y,x pairs.
  */
 static bool check(map<auto, vector< pair<size_t,size_t> > > const &m)
 {
	for(auto const &el : m)
	{
	  cout << el.first << endl;
	  auto v=el.second; //copy
	  auto const &firstpick=v.begin();
	  //v.erase(firstpick);
	  eat_a_single_chocochip_pattern_from_a_cookie(v,firstpick,none);
	  if(v.size()>0)
	  {
	   cout << el.first <<" some elements left "<< endl;
	   return false;
	  }
	  else
	   cout << el.first <<" OK "<< endl; 
	}
	return true;
 }
 
 /**
 * create a map by value. each element in map is a vector of y,x
 *
 * @param  array< array<size_t,size_t> > m
 */
 static map<int, vector< pair<size_t,size_t> > >  make_map( auto &&m)
 {
    map<int, vector< pair<size_t,size_t> > > ma;
	size_t mx=m[0].size(),my=m.size(); 
	for(size_t y=0;y<my;y++)
	{
	    auto const &row=m[y];
		for(size_t x=0;x<mx;x++)
		{
		   auto const &n=m[y][x];
		   if(n!=0)
		   ma[n].push_back(make_pair(y,x)); // operator [ ] automatically initializes an array on access if not exists, then add a newly created pair with y,x values.
		}
	}
	return ma;
 }
  
 /**
 * utility function to print a map
 *
 * @param  map<auto, vector< pair<int,int> > > m, vector is of y,x pairs.
 */
 static bool print_map(auto name,auto const &m)
 {
	cout<<endl;
	cout<<name<<":"<<endl;
	
	for(auto const &el : m) {
	  cout << el.first << endl;
	  for(auto const &p : el.second) {
		cout <<"  y="<< p.first <<", x="<< p.second << endl;
	  }
	}
    cout<<endl;
 }

};


 
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
 
 array<array<int, 7>, 7> cache =
 {{
 {0,0,0,0,0,0,0},
 {0,0,0,0,0,0,0},
 {0,0,0,0,0,0,0},
 {0,0,0,0,0,0,0},
 {0,0,0,0,0,0,0},
 {0,0,0,0,0,0,0},
 {0,0,0,0,0,0,0}
 }};
 

 bool print_array(auto name,auto const &m)
 {
	cout<<endl;
	cout<<name<<":"<<endl;
	
    size_t mx=m[0].size(),my=m.size(); 
	for(size_t y=0;y<my;y++)
	{
	    auto const &row=m[y];
		for(size_t x=0;x<mx;x++)
		{
		   auto const &n=row[x];
		   cout <<n;
		   if(x<mx-1)cout<<",";
		}
		cout<<endl;
	}
 }
 
 
int main (void)
{
         print_array("test3",test3);
 auto ma=MatrixValidator::make_map(test3);
         MatrixValidator::print_map("ma",ma);
 if(MatrixValidator::check(ma))
  cout <<" true"<<endl;
 else
  cout <<" false"<<endl;
 
 return 0;
}

/*
output
$ a.exe

test3:
0,0,0,0,0,0,0
0,1,0,1,1,1,0
0,1,0,1,0,1,0
0,1,0,1,0,1,0
0,1,0,0,0,1,0
0,1,1,1,1,1,0
0,0,0,0,0,0,0

ma:
1
  y=1, x=1
  y=1, x=3
  y=1, x=4
  y=1, x=5
  y=2, x=1
  y=2, x=3
  y=2, x=5
  y=3, x=1
  y=3, x=3
  y=3, x=5
  y=4, x=1
  y=4, x=5
  y=5, x=1
  y=5, x=2
  y=5, x=3
  y=5, x=4
  y=5, x=5

1
1 OK
 true

$ a.exe

test2:
2,1,1,0,0,0,0
2,1,1,0,0,0,0
2,0,0,0,0,0,0
2,2,0,0,1,1,0
0,0,0,0,1,1,0
0,0,0,0,0,0,0
0,0,0,0,0,0,0

ma:
1
  y=0, x=1
  y=0, x=2
  y=1, x=1
  y=1, x=2
  y=3, x=4
  y=3, x=5
  y=4, x=4
  y=4, x=5
2
  y=0, x=0
  y=1, x=0
  y=2, x=0
  y=3, x=0
  y=3, x=1

1
1 some elements left
 false



*/