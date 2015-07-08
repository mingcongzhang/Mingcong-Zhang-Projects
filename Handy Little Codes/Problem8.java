// Introduction
//
// Your teammate tried to fix the bug, but only managed to make it worse! Now the filter will only accept words that are already palindromes.
//
// You are now tasked with writing another add-on that determines how many different words you can send through the system given a set of characters.
//
// For example:
//
// bbaa can be sent in two different ways: abba and baab
// bbaacc can be sent in six different ways: baccab, abccba, acbbca, cabbac, bcaacb, and cbaabc.
//
// Input Specifications
//
// Your program will take
// A string S denoting the set of characters to be tested. All letters in the alphanumeric input will be lowercase (1 ≤ LENGTH(S) ≤ 500)
//
// Output Specifications
//
// Based on the input, print out the total number of unique palindromes that can be created from the input.
//
// Sample Input/Output
//
// INPUT
// bbaa
// OUTPUT
// 2
// EXPLANATION
// bbaa can be re-arranged to abba and baab, which are palindromes.
// INPUT
// abcdef
// OUTPUT
// 0
// EXPLANATION
// abcdef has no variations that are palindromes.
// INPUT
// bbaacc
// OUTPUT
// 6
// EXPLANATION
// bbaacc can make the following palindromes: baccab, bcaacb, cbaabc, cabbac, acbbca, abccba.
import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.HashSet;
import java.util.Scanner;
import java.util.Set;

public class Problem {

	public static String min(int[][] matrix) {
        int min = matrix[0][0];
        int a=0,b=0;
        for (int col = 0; col < matrix.length; col++) {
            for (int row = 0; row < matrix[col].length; row++) {
                if (min > matrix[col][row]) {
                    min = matrix[col][row];
                    a=col;
                    b=row;
                }
            }
        }
        return (a+","+b);
    }

	public static int minimum(int a, int b, int c) {
		return Math.min(Math.min(a, b), c);
	}

	public static int computeLevenshteinDistance(String str1, String str2) {
		int[][] distance = new int[str1.length() + 1][str2.length() + 1];

		for (int i = 0; i <= str1.length(); i++)
			distance[i][0] = i;
		for (int j = 1; j <= str2.length(); j++)
			distance[0][j] = j;

		for (int i = 1; i <= str1.length(); i++)
			for (int j = 1; j <= str2.length(); j++)
				distance[i][j] = minimum(
						distance[i - 1][j] + 1,
						distance[i][j - 1] + 1,
						distance[i - 1][j - 1]
								+ ((str1.charAt(i - 1) == str2
										.charAt(j - 1)) ? 0 : 1));

		return distance[str1.length()][str2.length()];
	}

	public static void main(String[] args) {

		Scanner stdin = new Scanner(System.in);
		String s = stdin.nextLine();
		int num = Integer.parseInt(s); // number of lines
		String[] input = new String[num * 2];
		for (int i = 0; i < num * 2; i++) {
			input[i] = stdin.nextLine();
		}
		stdin.close();

		// System.out.println(num);
		if (num != 0) {

			ArrayList<String> hs = new ArrayList<String>();
			int[][] dist = new int[num][num];
			for(int i = 0; i < num; i++){
				for(int j = num; j < 2 * num; j++){
					dist[i][j-num]=computeLevenshteinDistance(input[i],
							input[j]);
				}
			}
			for(int i = 0; i < num; i++){
				String st = min(dist);
				hs.add(st);
				int temp = Integer.parseInt(st.substring(0, st.indexOf(",")));
				for(int j = 0; j < num; j++){
					dist[temp][j] = Integer.MAX_VALUE;
				}
				int temp2 = Integer.parseInt(st.substring(st.indexOf(",")+1));
				for(int j = 0; j < num; j++){
					dist[j][temp2] = Integer.MAX_VALUE;
				}
			}
			Collections.sort(hs, new Comparator<String>() {
				@Override
				public int compare(String s1, String s2) {
					return s1.compareToIgnoreCase(s2);
				}
			});
			for (String ss : hs) {
				System.out.println(ss);
			}
		}
	}
}
