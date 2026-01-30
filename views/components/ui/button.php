<?php
/**
 * Button Component - Memora Movie
 * Botão reutilizável com 3 variantes
 * 
 * Props:
 * - variant: 'primary' | 'secondary' | 'ghost' (default: primary)
 * - href: string (se definido, renderiza <a> ao invés de <button>)
 * - text: string (texto do botão)
 * - class: string (classes adicionais)
 * - fullWidth: bool (w-full)
 * - type: string (button type, default: button)
 * - id: string (id do elemento)
 * - onclick: string (inline onclick handler)
 */

function renderButton($props = []) {
    $variant = $props['variant'] ?? 'primary';
    $href = $props['href'] ?? null;
    $text = $props['text'] ?? 'Clique aqui';
    $class = $props['class'] ?? '';
    $fullWidth = $props['fullWidth'] ?? false;
    $type = $props['type'] ?? 'button';
    $id = $props['id'] ?? '';
    $onclick = $props['onclick'] ?? '';
    
    // Classes base
    $baseStyles = "inline-flex items-center justify-center px-8 py-3 text-sm font-medium tracking-widest uppercase transition-all duration-300 ease-out focus:outline-none focus:ring-2 focus:ring-offset-2";
    
    // Variantes
    $variants = [
        'primary' => "bg-memora-wine text-white hover:bg-memora-wineLight focus:ring-memora-wine border border-transparent shadow-lg shadow-memora-wine/20",
        'secondary' => "bg-transparent text-memora-wine border border-memora-wine hover:bg-memora-wine hover:text-white focus:ring-memora-wine",
        'ghost' => "bg-transparent text-memora-wine hover:bg-memora-wine/5 focus:ring-memora-wine"
    ];
    
    $variantClass = $variants[$variant] ?? $variants['primary'];
    $widthClass = $fullWidth ? "w-full" : "";
    
    $allClasses = trim("$baseStyles $variantClass $widthClass $class");
    
    $idAttr = $id ? "id=\"$id\"" : "";
    $onclickAttr = $onclick ? "onclick=\"$onclick\"" : "";
    
    if ($href) {
        return "<a href=\"$href\" class=\"$allClasses transition-transform-scale\" $idAttr $onclickAttr>$text</a>";
    }
    
    return "<button type=\"$type\" class=\"$allClasses transition-transform-scale\" $idAttr $onclickAttr>$text</button>";
}

// Shorthand function for echo
function button($props = []) {
    echo renderButton($props);
}
?>
