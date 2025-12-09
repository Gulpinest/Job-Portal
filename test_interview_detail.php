<?php
require 'bootstrap/app.php';

use App\Models\InterviewSchedule;

$interview = InterviewSchedule::find(1);
if ($interview) {
    echo "Interview found: ID " . $interview->id . "\n";
    echo "Lowongan ID: " . $interview->id_lowongan . "\n";
    echo "Waktu: " . $interview->waktu_jadwal . "\n";
    echo "Type: " . $interview->type . "\n";

    // Load relation
    $interview->load(['lowongan.company']);
    echo "Lowongan loaded: " . $interview->lowongan?->judul . "\n";
    echo "Company loaded: " . $interview->lowongan?->company?->nama_perusahaan . "\n";
} else {
    echo "Interview not found\n";
}
?>
