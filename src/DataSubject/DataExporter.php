<?php

namespace Codelight\GDPR\DataSubject;

/**
 * Handle formatting and downloading data subject's data.
 *
 * Class DataManager
 *
 * @package Codelight\GDPR\DataSubject
 */
class DataExporter
{
    public function export(array $data, DataSubject $dataSubject, $format = 'html')
    {
        $data = $this->maybeUnserialize($data);

        do_action('gdpr/export', $data, $dataSubject->getEmail(), $dataSubject, $format);

        if ('html' === $format) {
            $this->downloadHTML($data, $dataSubject);
        } elseif ('json' === $format) {
            $this->downloadJSON($data, $dataSubject);
        }
    }

    /**
     * Download a data subject's data in human-readable format,
     * formatted as a table in an HTML document unless overridden.
     *
     * @param array       $data
     * @param DataSubject $dataSubject
     */
    protected function downloadHTML(array $data, DataSubject $dataSubject)
    {
        // Allow extensions to send a different response
        do_action('gdpr/export/html', $data, $dataSubject->getEmail(), $dataSubject);

        $filename = 'data_' . date("Y-m-d_H:i:s") . '.html';

        // By default, send a downloadable HTML file
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header("Content-Type: text/html");
        header("Content-Disposition: attachment; filename=\"{$filename}\";");
        header("Content-Transfer-Encoding: binary");

        echo $this->getHtmlData($data);
        exit;
    }

    /**
     * Download a data subject's data in machine-readable format,
     * formatted as JSON unless overridden.
     *
     * @param array       $data
     * @param DataSubject $dataSubject
     */
    protected function downloadJSON(array $data, DataSubject $dataSubject)
    {
        // Allow extensions to send a different response
        do_action('gdpr/export/json', $data, $dataSubject->getEmail(), $dataSubject);

        $filename = 'data_' . date("Y-m-d_H:i:s") . '.json';

        // By default, encode to JSON and send a JSON response
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
        header("Content-Type: application/json");
        header("Content-Disposition: attachment; filename=\"{$filename}\";");
        header("Content-Transfer-Encoding: binary");

        wp_send_json($data);
    }

    protected function getHtmlData($data)
    {
        $table = $this->formatAsTable($this->maybeUnserialize($data));
        return gdpr('view')->render('global/html-data', compact('table'));
    }

    protected function formatAsTable(array $data, $level = 0)
    {
        $output = "<table class='level-{$level}'>";
        foreach ($data as $key => $value) {
            $output .= "<tr>";

                // Output key
                $output .= "<td class='key'>";
                $output .= esc_html($key);
                $output .= "</td>";

                // Output value
                $output .= "<td class='value'>";

                // Account for arrays with just one item, such as usermeta
                if (is_array($value) && 1 === count($value)) {
                    $value = $value[0];
                }

                // In case of arrays, recurse
                if (is_array($value)) {
                    $output .= $this->formatAsTable($value, ($level + 1));
                } else {
                    $output .= esc_html($value);
                }
                $output .= "</td>";

            $output .= "</tr>";
        }

        $output .= "</table>";
        return $output;
    }

    /**
     * Recursively maybe unserialize data
     *
     * @param array $data
     * @return array
     */
    protected function maybeUnserialize(array $data)
    {
        foreach ($data as &$datum) {
            if (is_array($datum)) {
                $datum = $this->maybeUnserialize($datum);
            } else {
                $datum = maybe_unserialize($datum);
            }
        }

        return $data;
    }
}
