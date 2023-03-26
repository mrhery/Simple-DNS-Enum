// See https://aka.ms/new-console-template for more information

using System.Net;

Console.WriteLine("Welcome to C# DNS Enum!");

void main()
{
    Console.Write("Domain Name: ");
    string domain = Console.ReadLine();

    Console.Write("Wordlist Path (optional): ");
    string wordlistPath = Console.ReadLine();

    if(string.IsNullOrEmpty(wordlistPath) || string.IsNullOrWhiteSpace(wordlistPath))
    {
        wordlistPath = Path.GetFullPath("./dns.txt");
    }

    if (!File.Exists(wordlistPath))
    {
        Console.WriteLine("Wordlist path is invalid: " + wordlistPath);
        Console.WriteLine("");

        main();
    }

    List<string> subs = new List<string>(File.ReadLines(wordlistPath));

    Console.WriteLine("====================================\nDNS Enumeration is starting...");

    foreach(string sub in subs)
    {
        try
        {
            IPAddress[] ips = Dns.GetHostAddresses(sub + "." + domain);

            foreach (var ip in ips)
            {
                Console.WriteLine(sub + "." + domain + " => " + ip.ToString());
            }
        }catch(Exception ex)
        {
            continue;
        }
    }

    Console.WriteLine("====================================\nDNS Enumeration is completed");
    Console.WriteLine("");


    main();
}

main();
