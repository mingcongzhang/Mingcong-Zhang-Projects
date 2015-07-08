

import java.util.Scanner;



/*Introduction

As part of a team project to build an instant messaging program, you have to work around your teammate's buggy compliance filter.

The compliance filter works based on an algorithm which determines if certain messages can pass through the system and if they should be flagged. Usually the compliance filter system has a dictionary of black listed words which it filters out.

However, due to your teammate's programming error the compliance filter system appears to only allow words that are palindromes or anagrams of palindromes (i.e. can make a palindrome when re-arranged).

For example:

abba
aabb (this one is an anagram of abba or baab)
civic
deified
mom
mmo
radar
While a fix is being worked on, you are tasked with writing an add on that will determine if a word can pass through the this system.


Input Specifications

Your program will take
A string S denoting the word to be tested. All letters in the alphanumeric input will be lowercase (1 � LENGTH(S) � 500), and there may be numbers and symbols.

Output Specifications

Based on the input,
Print out yes if the input would pass through the system
Print out no if the input would fail the system
*/
public class Problem6 {

	static boolean checkPalindrome(String input) {
		int[] count = new int[128];
		for (int i = 0; i < input.length(); i++) {
			char ch = input.charAt(i);
			count[Math.abs(ch - 32)]++;
			//System.out.println(count[ch-'a']);
		}
		int oddOccur = 0;
		for (int cnt : count) {
			if (oddOccur > 1) //can have at most 1 odd
				return false;
			if (cnt % 2 == 1)
				oddOccur++;
		}
		return true;
	}

	public static void main(String[] args) {

		Scanner stdin = new Scanner(System.in);
		String s = stdin.nextLine();
		stdin.close();

		if (checkPalindrome(s)){
			System.out.println("yes");
		}else{
			System.out.println("no");
		}
	}

}
