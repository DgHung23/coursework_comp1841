from __future__ import annotations

import math
from pathlib import Path

from PIL import Image, ImageDraw, ImageFont, ImageOps


ROOT = Path(r"C:\xampp\htdocs\COMP1841\CourseWork\scratch\report_assets")
OUT = ROOT


def find_font(candidates, size):
    for candidate in candidates:
        path = Path(candidate)
        if path.exists():
            return ImageFont.truetype(str(path), size=size)
    return ImageFont.load_default()


FONT_REG = find_font(
    [
        r"C:\Windows\Fonts\calibri.ttf",
        r"C:\Windows\Fonts\arial.ttf",
        r"C:\Windows\Fonts\segoeui.ttf",
    ],
    24,
)
FONT_REG_22 = find_font(
    [
        r"C:\Windows\Fonts\calibri.ttf",
        r"C:\Windows\Fonts\arial.ttf",
        r"C:\Windows\Fonts\segoeui.ttf",
    ],
    22,
)
FONT_REG_18 = find_font(
    [
        r"C:\Windows\Fonts\calibri.ttf",
        r"C:\Windows\Fonts\arial.ttf",
        r"C:\Windows\Fonts\segoeui.ttf",
    ],
    18,
)
FONT_REG_16 = find_font(
    [
        r"C:\Windows\Fonts\calibri.ttf",
        r"C:\Windows\Fonts\arial.ttf",
        r"C:\Windows\Fonts\segoeui.ttf",
    ],
    16,
)
FONT_BOLD = find_font(
    [
        r"C:\Windows\Fonts\calibrib.ttf",
        r"C:\Windows\Fonts\arialbd.ttf",
        r"C:\Windows\Fonts\segoeuib.ttf",
    ],
    22,
)
FONT_BOLD_28 = find_font(
    [
        r"C:\Windows\Fonts\calibrib.ttf",
        r"C:\Windows\Fonts\arialbd.ttf",
        r"C:\Windows\Fonts\segoeuib.ttf",
    ],
    28,
)
FONT_MONO = find_font(
    [
        r"C:\Windows\Fonts\consola.ttf",
        r"C:\Windows\Fonts\cour.ttf",
    ],
    16,
)


BG = "#f6f8fb"
PANEL = "#ffffff"
BORDER = "#d7dfe8"
BLUE = "#2e74b5"
BLUE_DARK = "#1f4d78"
TEXT = "#1f2937"
MUTED = "#5b6470"
SOFT = "#e8eef5"
ACCENT = "#dbe9f7"
GREEN = "#d9efe2"
GREEN_DARK = "#1e6b46"
GOLD = "#f4ead0"
GOLD_DARK = "#7a5a00"
RED = "#f7d9d9"
RED_DARK = "#8a1f1f"


def add_panel(canvas, x, y, w, h, title, image_path=None, subtitle=None):
    draw = ImageDraw.Draw(canvas)
    draw.rounded_rectangle((x, y, x + w, y + h), radius=18, fill=PANEL, outline=BORDER, width=2)
    header_h = 44
    draw.rounded_rectangle((x, y, x + w, y + header_h), radius=18, fill=SOFT)
    draw.rectangle((x, y + header_h - 18, x + w, y + header_h), fill=SOFT)
    draw.text((x + 18, y + 10), title, fill=BLUE_DARK, font=FONT_BOLD)
    if subtitle:
        draw.text((x + w - 18 - draw.textlength(subtitle, font=FONT_REG_16), y + 13), subtitle, fill=MUTED, font=FONT_REG_16)
    if image_path:
        img = Image.open(image_path).convert("RGB")
        target = (w - 36, h - header_h - 24)
        fitted = ImageOps.contain(img, target, method=Image.Resampling.LANCZOS)
        bg = Image.new("RGB", target, "#ffffff")
        paste_x = (target[0] - fitted.width) // 2
        paste_y = (target[1] - fitted.height) // 2
        bg.paste(fitted, (paste_x, paste_y))
        # soft shadow border inside the panel
        inner_x = x + 18
        inner_y = y + header_h + 12
        canvas.paste(bg, (inner_x, inner_y))
        draw.rectangle((inner_x, inner_y, inner_x + target[0], inner_y + target[1]), outline=BORDER, width=1)
    return draw


def make_collage(name, specs):
    cols = 2
    rows = math.ceil(len(specs) / cols)
    panel_w = 760
    panel_h = 500
    gap_x = 24
    gap_y = 24
    margin = 28
    title_band = 0
    width = margin * 2 + cols * panel_w + (cols - 1) * gap_x
    height = margin * 2 + rows * panel_h + (rows - 1) * gap_y
    canvas = Image.new("RGB", (width, height + title_band), BG)
    draw = ImageDraw.Draw(canvas)
    for idx, (title, image_name, subtitle) in enumerate(specs):
        r = idx // cols
        c = idx % cols
        x = margin + c * (panel_w + gap_x)
        y = margin + r * (panel_h + gap_y)
        add_panel(canvas, x, y, panel_w, panel_h, title, ROOT / image_name, subtitle)
    canvas.save(OUT / name)


def rounded_box(draw, box, fill, outline=BLUE_DARK, width=3, radius=20):
    draw.rounded_rectangle(box, radius=radius, fill=fill, outline=outline, width=width)


def wrap_lines(text, font, max_width):
    words = text.split()
    lines = []
    current = ""
    dummy = Image.new("RGB", (10, 10))
    draw = ImageDraw.Draw(dummy)
    for word in words:
        candidate = word if not current else current + " " + word
        if draw.textlength(candidate, font=font) <= max_width:
            current = candidate
        else:
            if current:
                lines.append(current)
            current = word
    if current:
        lines.append(current)
    return lines


def draw_box_with_text(draw, xy, title, body_lines, fill, outline=BLUE_DARK):
    x1, y1, x2, y2 = xy
    rounded_box(draw, xy, fill, outline=outline, width=3, radius=18)
    draw.text((x1 + 16, y1 + 12), title, fill=BLUE_DARK, font=FONT_BOLD)
    y = y1 + 44
    for line in body_lines:
        draw.text((x1 + 16, y), line, fill=TEXT, font=FONT_REG_18)
        y += 23


def make_erd():
    w, h = 1600, 980
    canvas = Image.new("RGB", (w, h), BG)
    draw = ImageDraw.Draw(canvas)
    draw.text((60, 40), "Entity Relationship Diagram", fill=BLUE_DARK, font=FONT_BOLD_28)
    draw.text((60, 86), "The forum uses a small relational model with one author table, one module table, one post table, and a junction table for many-to-many post-module links.", fill=MUTED, font=FONT_REG_18)

    draw_box_with_text(
        draw,
        (70, 160, 420, 390),
        "accounts",
        [
            "id PK",
            "username",
            "email",
            "hashed_password",
            "role",
            "display_name",
            "bio",
        ],
        ACCENT,
    )
    draw_box_with_text(
        draw,
        (1080, 160, 1450, 330),
        "category",
        [
            "id PK",
            "name",
            "description",
        ],
        "#f1f7ee",
        outline=GREEN_DARK,
    )
    draw_box_with_text(
        draw,
        (535, 540, 1065, 820),
        "post",
        [
            "id PK",
            "author_id FK",
            "title",
            "content",
            "image",
            "created_at",
            "updated_at",
        ],
        "#fff7ea",
        outline=GOLD_DARK,
    )
    draw_box_with_text(
        draw,
        (640, 150, 960, 310),
        "post_category",
        [
            "post_id PK/FK",
            "category_id PK/FK",
        ],
        "#f7edf4",
        outline="#8b3f74",
    )

    # connectors
    draw.line((420, 275, 640, 220), fill=BLUE_DARK, width=4)
    draw.line((960, 220, 1080, 245), fill=GREEN_DARK, width=4)
    draw.line((800, 310, 800, 540), fill="#8b3f74", width=4)
    draw.line((640, 230, 420, 275), fill=BLUE_DARK, width=4)
    draw.line((960, 230, 1080, 245), fill=GREEN_DARK, width=4)
    draw.line((800, 310, 800, 540), fill="#8b3f74", width=4)

    # cardinality labels
    draw.text((437, 250), "1", fill=BLUE_DARK, font=FONT_BOLD)
    draw.text((1035, 235), "1", fill=GREEN_DARK, font=FONT_BOLD)
    draw.text((790, 325), "many", fill="#8b3f74", font=FONT_REG_16)
    draw.text((760, 500), "many", fill=GOLD_DARK, font=FONT_REG_16)
    draw.text((1250, 120), "one module can be attached to many posts", fill=MUTED, font=FONT_REG_16)
    draw.text((120, 420), "each post belongs to one author", fill=MUTED, font=FONT_REG_16)
    draw.text((558, 840), "each post can reference multiple modules through the junction table", fill=MUTED, font=FONT_REG_16)

    canvas.save(OUT / "erd_diagram.png")


def make_navigation():
    w, h = 1600, 980
    canvas = Image.new("RGB", (w, h), BG)
    draw = ImageDraw.Draw(canvas)
    draw.text((60, 40), "Navigation Structure", fill=BLUE_DARK, font=FONT_BOLD_28)
    draw.text((60, 86), "Public visitors follow a simple top-level menu, while authenticated users gain access to question posting and admin-only tools.", fill=MUTED, font=FONT_REG_18)

    nodes = {
        "home": (80, 180, 320, 250, "Home", ["index.php", "Landing page"]),
        "questions": (390, 180, 660, 250, "Questions", ["posts.php", "Filter and browse"]),
        "detail": (700, 180, 980, 250, "Question detail", ["post_view.php", "Full post + image"]),
        "ask": (390, 330, 660, 400, "Ask Question", ["post_action.php", "Create or edit"]),
        "contact": (80, 330, 320, 400, "Contact", ["contact.php", "Message admin"]),
        "auth": (980, 180, 1280, 250, "Login / Sign Up", ["login.php", "signup.php"]),
        "admin": (980, 330, 1280, 400, "Admin Area", ["admin/index.php", "Role-gated"]),
        "users": (1320, 280, 1550, 350, "Users", ["admin/users.php", "Role control"]),
        "modules": (1320, 380, 1550, 450, "Modules", ["admin/categories.php", "Add/delete"]),
    }

    def draw_node(key, fill, outline=BLUE_DARK):
        x1, y1, x2, y2, title, lines = nodes[key]
        draw.rounded_rectangle((x1, y1, x2, y2), radius=18, fill=fill, outline=outline, width=3)
        draw.text((x1 + 16, y1 + 12), title, fill=BLUE_DARK, font=FONT_BOLD)
        y = y1 + 46
        for line in lines:
            draw.text((x1 + 16, y), line, fill=TEXT, font=FONT_REG_18)
            y += 22

    draw_node("home", ACCENT)
    draw_node("questions", "#f1f7ee", outline=GREEN_DARK)
    draw_node("detail", "#fff7ea", outline=GOLD_DARK)
    draw_node("ask", "#eef4fb")
    draw_node("contact", "#f8f0f3", outline="#8b3f74")
    draw_node("auth", "#eef4fb")
    draw_node("admin", "#efeef9", outline="#5c4b9a")
    draw_node("users", "#eef4fb")
    draw_node("modules", "#eef4fb")

    def arrow(start, end, color=BLUE_DARK):
        draw.line((start[0], start[1], end[0], end[1]), fill=color, width=4)
        # arrow head
        dx = end[0] - start[0]
        dy = end[1] - start[1]
        length = max((dx * dx + dy * dy) ** 0.5, 1)
        ux, uy = dx / length, dy / length
        left = (end[0] - ux * 16 - uy * 8, end[1] - uy * 16 + ux * 8)
        right = (end[0] - ux * 16 + uy * 8, end[1] - uy * 16 - ux * 8)
        draw.polygon([end, left, right], fill=color)

    arrow((320, 215), (390, 215))
    arrow((650, 215), (700, 215), GREEN_DARK)
    arrow((320, 215), (320, 365), "#8b3f74")
    arrow((320, 215), (980, 215), "#5c4b9a")
    arrow((1120, 250), (1120, 330), "#5c4b9a")
    arrow((1280, 215), (1320, 305), "#5c4b9a")
    arrow((1280, 215), (1320, 405), "#5c4b9a")
    arrow((660, 365), (980, 365), BLUE_DARK)

    draw.text((210, 265), "public", fill=MUTED, font=FONT_REG_16)
    draw.text((1050, 265), "session-based access", fill=MUTED, font=FONT_REG_16)
    draw.text((1500, 455), "admin-only", fill=MUTED, font=FONT_REG_16)

    canvas.save(OUT / "navigation_diagram.png")


def main():
    make_collage(
        "public_workflow.png",
        [
            ("Questions filtered by module", "questions_filtered.png", "browse"),
            ("Question detail with screenshot", "question_detail.png", "view"),
            ("Ask Question form", "ask_question.png", "create"),
            ("Contact Administrator", "contact.png", "support"),
        ],
    )
    make_collage(
        "admin_workflow.png",
        [
            ("Login screen", "login.png", "auth"),
            ("Admin dashboard", "admin_dashboard.png", "overview"),
            ("Manage users", "admin_users.png", "roles"),
            ("Manage modules", "admin_modules.png", "catalogue"),
        ],
    )
    make_erd()
    make_navigation()


if __name__ == "__main__":
    main()
