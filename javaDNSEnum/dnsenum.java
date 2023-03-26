
import java.io.*;
import java.util.ArrayList;
import java.net.*;

class DNSEnum {
	public static void main(String[] args){
		String wordlist = "dns.txt";
		String domain = "";
		
		if(args.length == 1){
			domain = args[0];
		}else if(args.length == 2){
			domain = args[1];
			wordlist = args[0];
		}else{
			System.out.println("Arg Error: Only accept maximum 2 argument. Example: java DNSEnum <wordlist path> <domain name>");
			
			return;
		}
		
		File f = new File(wordlist);
		
		if(!f.exists()){
			System.out.println("Wordlist file is not exists: " + wordlist);
			
			return;
		}
		
		System.out.println("=====================================\nDNS Enumeration is starting...");
		
		String[] words = readWordList(wordlist);
        for (String word : words) {
			try {
				InetAddress[] addresses = InetAddress.getAllByName(word + "." + domain);

				for (InetAddress address : addresses) {
					System.out.println(word + "." + domain + " => " + address.getHostAddress());
				}
			} catch (UnknownHostException ex) {
				continue;
			}
        }
		
		System.out.println("\n=====================================\nDNS Enumeration is completed\n\n");
	}
	
	public static String[] readWordList(String filename) {
        ArrayList<String> wordList = new ArrayList<>();
        try (BufferedReader reader = new BufferedReader(new FileReader(filename))) {
            String line;
            while ((line = reader.readLine()) != null) {
                wordList.add(line);
            }
        } catch (IOException e) {
            e.printStackTrace();
        }
        return wordList.toArray(new String[0]);
    }
}