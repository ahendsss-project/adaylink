<?php

namespace App\Livewire\Admin;

use App\Models\Template;
use Livewire\Component;

class Templates extends Component
{
    public function delete(int $templateId): void
    {
        $template = Template::findOrFail($templateId);

        // Prevent deleting if websites are using this template
        if ($template->websiteSettings()->exists()) {
            session()->flash('error', "Template '{$template->name}' tidak bisa dihapus karena sedang digunakan oleh website.");

            return;
        }

        $template->delete();
        session()->flash('success', "Template '{$template->name}' berhasil dihapus.");
        $this->dispatch('$refresh');
    }

    public function toggleActive(int $templateId): void
    {
        $template = Template::findOrFail($templateId);
        $template->update(['is_active' => ! $template->is_active]);
        session()->flash('success', "Template '{$template->name}' " . ($template->is_active ? 'diaktifkan' : 'dinonaktifkan') . '.');
        $this->dispatch('$refresh');
    }

    public function render()
    {
        $templates = Template::orderBy('tier', 'asc')->orderBy('name', 'asc')->get();

        return view('livewire.admin.templates', compact('templates'))
            ->layout('components.layouts.admin')
            ->title('Manage Templates - Admin adaylink');
    }
}
