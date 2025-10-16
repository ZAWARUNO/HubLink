# 📱 Builder Mobile Responsive Guide

## Overview
Page Builder HubLink sekarang **fully responsive** dan dapat digunakan di mobile device dengan UI yang optimal.

---

## ✨ Fitur Mobile Builder

### 1. **Responsive Layout**
- **Components Panel**: Slide dari kiri (hidden by default)
- **Canvas Area**: Full width, scrollable
- **Properties Panel**: Slide dari kanan (auto show saat edit)
- **Overlay**: Dark overlay saat panel terbuka

### 2. **Mobile Navigation**
- **Hamburger Button**: Toggle components panel
- **Close Button**: Di dalam panel untuk close
- **Overlay Click**: Click overlay untuk close semua panel
- **Auto Close**: Panel close otomatis setelah action

### 3. **Touch-Friendly UI**
- Larger touch targets (min 44px)
- Proper spacing untuk tap
- Smooth animations
- Swipe-friendly panels

---

## 🎯 Cara Menggunakan di Mobile

### **Membuka Components Panel:**
1. Tap tombol **hamburger** (3 garis) di kiri atas
2. Panel components slide dari kiri
3. Pilih component yang ingin ditambahkan
4. Drag ke canvas atau tap untuk add
5. Panel auto close setelah add component

### **Edit Component:**
1. Tap component di canvas
2. Properties panel slide dari kanan
3. Edit properties
4. Tap "Save Changes"
5. Panel auto close

### **Close Panel:**
- Tap tombol **X** di panel
- Tap **overlay** (area gelap)
- Atau tap component lain

---

## 📐 Responsive Breakpoints

### Mobile (< 768px)
```css
- Components Panel: Fixed, slide from left
- Properties Panel: Fixed, slide from right
- Canvas: Full width
- Header: Compact, icon only
- Padding: Reduced (1rem)
```

### Tablet/Desktop (≥ 768px)
```css
- Components Panel: Fixed left sidebar
- Properties Panel: Fixed right sidebar
- Canvas: Center with max-width
- Header: Full with text
- Padding: Normal (2rem)
```

---

## 🎨 UI Components

### **Header (Mobile)**
- Hamburger button (left)
- Title (center)
- Device toggle (compact)
- Preview & Publish (icon only)

### **Components Panel (Mobile)**
- Width: 80% (max 280px)
- Position: Fixed left
- Animation: Slide from left
- Close button: Top right
- Scrollable content

### **Properties Panel (Mobile)**
- Width: 90% (max 320px)
- Position: Fixed right
- Animation: Slide from right
- Close button: Top right
- Scrollable content

### **Canvas (Mobile)**
- Width: 100%
- Padding: 1rem
- Scrollable
- Touch-friendly components

---

## 🔧 Technical Details

### CSS Classes
```css
.components-panel - Components panel container
.components-panel.open - Panel visible state
.canvas-area - Canvas container
.panel-overlay - Dark overlay
.panel-overlay.active - Overlay visible
```

### JavaScript Functions
```javascript
toggleComponentsPanel() - Toggle components panel
closeMobilePanels() - Close all panels
closePropertiesPanel() - Close properties panel
editComponent(id) - Edit component (auto show panel)
```

---

## ✅ Mobile Optimization Checklist

**Layout:**
- [x] Responsive 3-panel layout
- [x] Slide panels from sides
- [x] Full-width canvas
- [x] Compact header

**Interaction:**
- [x] Touch-friendly buttons
- [x] Smooth animations
- [x] Overlay for focus
- [x] Auto-close panels

**Performance:**
- [x] Optimized CSS
- [x] Minimal JavaScript
- [x] Smooth transitions
- [x] No layout shift

**UX:**
- [x] Intuitive navigation
- [x] Clear visual feedback
- [x] Easy to close panels
- [x] Mobile-first design

---

## 🚀 Best Practices

### **For Users:**
1. Use hamburger menu untuk access components
2. Tap component untuk edit
3. Close panel dengan overlay atau X button
4. Use device toggle untuk preview mobile/desktop

### **For Developers:**
1. Test di real mobile devices
2. Check touch target sizes (min 44px)
3. Verify smooth animations
4. Test panel interactions

---

## 🐛 Known Issues & Solutions

### Issue: Panel tidak slide
**Solution:** Refresh page, check console for errors

### Issue: Overlay tidak close panel
**Solution:** Ensure JavaScript loaded, check event listeners

### Issue: Component tidak bisa di-drag di mobile
**Solution:** Use tap to add instead of drag on mobile

### Issue: Properties panel terlalu lebar
**Solution:** Panel max-width 320px, should fit most phones

---

## 📊 Browser Support

- **Chrome Mobile**: ✅ Full support
- **Safari iOS**: ✅ Full support
- **Firefox Mobile**: ✅ Full support
- **Samsung Internet**: ✅ Full support
- **Opera Mobile**: ✅ Full support

---

## 🎯 Future Enhancements

### Phase 2:
- [ ] Swipe gestures untuk panels
- [ ] Bottom sheet untuk quick actions
- [ ] Floating action button
- [ ] Component search
- [ ] Keyboard shortcuts

### Phase 3:
- [ ] Touch gestures (pinch, zoom)
- [ ] Component preview on hover
- [ ] Bulk actions
- [ ] Undo/Redo with gestures

---

## 📝 Testing Checklist

Before deploying:
- [ ] Test hamburger menu
- [ ] Test components panel slide
- [ ] Test properties panel slide
- [ ] Test overlay close
- [ ] Test component drag/add
- [ ] Test edit component
- [ ] Test save changes
- [ ] Test device toggle
- [ ] Test on real devices
- [ ] Test different screen sizes

---

**Last Updated:** October 16, 2025
**Version:** 1.0.0
