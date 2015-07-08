// Introduction
//
// A palindrome is a word that reads the same backward and forward.
// Given a string S, you are allowed to convert it to a palindrome by adding 0 or more characters in front of it.
// Find the length of the shortest palindrome that you can create from S by applying the above transformation.
//
// Input Specifications
//
// Your program will take
// A string S ( 1 ≤ Length(S) ≤ 100) where each character of S will be
// a lowercase alphabet (Between 'a' to 'z')
//
// Output Specifications
//
// For each input, print out an integer L denoting the length of the shortest palindrome you can
// generate from S.
//
// Sample Input/Output
//
// INPUT
// baaa
// OUTPUT
// 7
// EXPLANATION
// The shortest palindrome you can construct from 'baaa' is 'aaabaaa'.
import java.util.Scanner;

public class Problem2
{

	static boolean isPal(String s){
		int n = s.length();
		  for (int i=0;i<(n / 2) + 1;++i) {
		     if (s.charAt(i) != s.charAt(n - i - 1)) {
		         return false;
		     }
		  }
		  return true;
	}

    public static void main(String[] args)
    {

       Scanner stdin = new Scanner(System.in);
       String s = stdin.nextLine();
       stdin.close();

       int ct = 0;
       int ct2 = 0;
       if(isPal(s)){
     	  	System.out.println(s.length());
       }else{

     	  	for(int i=0;i<s.length();i++){
     	  		String b = new String(s);
     	  		for(int j=i;j<s.length();j++){
	     	  		b=s.charAt(j)+b;
	     	  		//System.out.println(b);
	     	  		if(isPal(b)){
	     	  			if(ct!=0&&ct>b.length()||ct==0){
	     	  				ct = b.length();
	     	  			}
	     	  		}
     	  		}
     	  	}
     	  	for(int i=s.length()-1;i>=0;i--){
     	  		String b = new String(s);
     	  		for(int j=i;j>=0;j--){
	     	  		b=s.charAt(i)+b;
	     	  		if(isPal(b)){
	     	  			if(ct2!=0&&ct2>b.length()||ct2==0){
	     	  				ct2 = b.length();
	     	  			}
	     	  		}
     	  		}
     	  	}

     	  	int f = s.length()*2-1;
     	  	//System.out.println(ct+" "+ ct2+ " "+f );


     	  	if(ct2!=0&&ct2>f || ct > f&&ct!=0){
     	 	  	System.out.println(f);
     	  	}else if(ct2>=ct&&ct!=0||ct2<ct&&ct2==0){
     	  		System.out.println(ct);
     	  	}else if(ct2<=ct&&ct2!=0||ct2>ct&&ct==0){
     	  		System.out.println(ct2);
     	  	}

       }
    }

}
