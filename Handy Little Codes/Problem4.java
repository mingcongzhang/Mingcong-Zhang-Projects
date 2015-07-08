

import java.io.BufferedInputStream;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.Iterator;
import java.util.Scanner;



/*Introduction

It's laundry day, and, as usual, you've been putting this off for quite some time. Also, unfortunately, you lacked the foresight to actually ensure all your dirty laundry stayed in your hamper whilst it accumulated (what? we can't ALL be underwear basketball pros!).

Begrudgingly, you've gathered up all the clothing you could find and sent them through the wash. Now you have a disheveled pile of clean, albiet disorganized, accoutrements. You come to the realization that you probably lost some items in the fray, so now it's time to fold and figure out what's gone missing!

To get a good idea of the state of your wardrobe, count up the number of distinct shirts, pants, and underwear you have as you go through the laundry. Also pair up your socks, noting the number of pairs of each kind of sock and if there are any lonely souls (single (and ready to mingle) socks).


Input Specifications

Each article of clothing will have its own separate line. You have a penchant for hoarding, so there is no guarantee as to the number of pieces, but you can assure yourself that each article can be easily categorized by description (name).

Articles of clothing will be fed in as line-delimited list. See below for examples.


Output Specifications

Output should be an alphabetically (case-insensitive) sorted, line-delimited list of the articles of clothing along with their count. Each field (count, category) should be separated by a pipe (|). If you come across a sock without a soulmate, the count should be designated by a 0 (zero). Socks that are in pairs should be on separate lines from the socks of the same category without pairs, and should come before the pairless sock. See below for examples.

*/
public class Problem4 {


	public static void main(String[] args)
  {

      Scanner stdin = new Scanner(System.in);
      ArrayList<String> arr = new ArrayList<String>();
      while(stdin.hasNextLine())
      {
    	  String s = stdin.nextLine();
    	  if(s.trim().equals("")){
    		  break;
    	  }
          arr.add(s);
      }
      stdin.close();
      Collections.sort(arr, new Comparator<String>(){
     	 	@Override
     	 	public int compare(String s1, String s2){
     	 		return s1.compareToIgnoreCase(s2);
     	 	}
      });

      Iterator<String> it = arr.iterator();
      String st = "";
      int ct = 0;
      while(it.hasNext()){
    	  String tmp = it.next();
    	  //System.out.println(tmp);
    	  if(st.equals(tmp)){
    		  ct++;
    	  }else{
    		  if(st.equals("")){
    			  st = tmp;
    			  ct++;
    		  }else{
    			  if(st.toLowerCase().contains("sock".toLowerCase())){
    				  int pairs = ct/2;
    				  int left = ct%2;
    				  if(left!=0&&pairs!=0){
    					  System.out.println(pairs+"|"+st);
    					  System.out.println(0+"|"+st);
    				  }else{
    					  System.out.println(pairs+"|"+st);
    				  }
    			  }else{
    				  System.out.println(ct+"|"+st);
    			  }
    			  st = tmp;
    			  ct = 1;
    		  }
    	  }

      }
      if(st.toLowerCase().contains("sock".toLowerCase())){
		  int pairs = ct/2;
		  int left = ct%2;
		  if(left!=0&&pairs!=0){
			  System.out.println(pairs+"|"+st);
			  System.out.println(0+"|"+st);
		  }else{
			  System.out.println(pairs+"|"+st);
		  }
	  }else{
		  System.out.println(ct+"|"+st);
	  }
     //System.out.println(arr.toString());
  }
}
