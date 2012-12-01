<?php

namespace Req\Console;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ReqCommand extends Command
{
    protected function configure()
    {
        $this->setName('make')
        ->setDescription('Make a request using the CLI')
        ->addArgument(
            'filename',
            InputArgument::REQUIRED,
            'A request template JSON file'
        )
        ->addArgument(
            'datafile',
            InputArgument::OPTIONAL,
            "A file to be POSTed as a string"
        )
        ->addOption(
            'inspect',
            'i',
            InputOption::VALUE_NONE,
            "Print the request data to the terminal"
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename_or_url = $input->getArgument('filename');
        $dataFile        = $input->getArgument('datafile');

        if (filter_var($filename_or_url, FILTER_VALIDATE_URL)) {
            // Check to see if there's a second argument, a file to POST.
            if ($dataFile) {
                // Ensure it's a file.
                if (!is_file($dataFile)) {
                    $output->writeln('<error>The file you provided for the \'datafile\' argument doesn\'t seem to exist, please check and try again.</error>');
                    exit(1);
                } else {
                    // Send a POST request with this as the data.
                    $response = Req::create($filename_or_url)->post(file_get_contents($dataFile));
                }
            } else {
                // Just GET the URL.
                $response = Req::create($filename_or_url)->get();
            }
        } else {
            // Ensure the provided filepath is a file.
            if (!is_file($filename_or_url)) {
                $output->writeln("<error>Doesn't look like you provided a valid URL or filename.</error>");
                $output->writeln("<error>Wanting to request a URL? Make sure you add http:// or https://.</error>");
                $output->writeln("<error>Wanting to use a JSON request file? Make sure it exists.</error>");
                exit(1);
            }

            $contents = file_get_contents($filename_or_url);
            $json = json_decode($contents);

            // Attempt to parse the JSON.
            if (!is_null($json)) {
                // The only thing we require is the URL.
                if (!isset($json->url)) {
                    $output->writeln("<error>No 'url' value provided, where will we send the request?</error>");
                } elseif (isset($json->method) && !in_array(strtolower($json->method), array('get', 'post'))) {
                    $output->writeln("<error>No compatible method supplied, must be 'get' or 'post'.</error>");
                } else {
                    $req = Req::create($json->url);

                    if (isset($json->headers) && count($json->headers) > 0) {
                        $req->headers((array) $json->headers);
                    }
                    if (!isset($json->method) || strtolower($json->method) == 'get') {
                        $response = $req->get();
                    } elseif (strtolower($json->method) == 'post') {
                        $data_file = isset($dataFile) && !empty($dataFile) ? file_get_contents($dataFile) : null;
                        $json_data = isset($json->data) && count($json->data) > 0 ? $json->data : null;

                        if ($data_file) {
                            $data = (string) $data_file;
                        } else {
                            $data = $json->data;
                        }
                        $response = $req->post($data);
                    }
                }
            } else {
                $output->writeln("<error>The file '{$filename_or_url}' does not appear to contain valid JSON.</error>");
            }

        }
        if ($input->getOption('inspect')) {
            $output->writeln("<fg=yellow;options=bold>Response Info</fg=yellow;options=bold>");
            foreach ($response->info as $key => $item) {
                $output->writeln("<info>{$key}: {$item}</info>");
            }
        } else {
            $output->writeln($response->body);
        }
    }
}
