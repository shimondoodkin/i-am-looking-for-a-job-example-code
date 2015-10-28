A c++ code required. to run on a chip of kinnect camera. to check validity of input matricies.

```text

a matix like this:

0,1,1,0,0,0,0
0,1,1,0,2,2,0
0,0,0,0,2,2,0
0,0,0,0,0,0,0
0,3,3,0,0,0,0
0,3,3,0,4,4,0
0,0,0,0,4,4,0


good:

0,1,1,0,0,0,0
0,1,1,0,0,0,0
0,0,0,0,0,0,0
0,0,0,0,0,0,0
0,0,0,0,0,0,0
0,0,0,0,0,0,0
0,0,0,0,0,0,0
(good - no seperate islands of same number)

not good:

0,1,1,0,0,0,0
0,1,1,0,0,0,0
0,0,0,0,0,0,0
0,0,0,0,1,1,0
0,0,0,0,1,1,0
0,0,0,0,0,0,0
0,0,0,0,0,0,0
(bad - has seperate islands of same number)

```

