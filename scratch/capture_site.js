const fs = require('node:fs/promises');
const path = require('node:path');
const { chromium } = require('playwright-core');

const baseUrl = 'http://localhost/COMP1841/CourseWork/';
const edgePath = 'C:\\Program Files (x86)\\Microsoft\\Edge\\Application\\msedge.exe';
const outDir = path.resolve(__dirname, 'report_assets');

async function capturePage(page, url, fileName) {
  await page.goto(url, { waitUntil: 'networkidle' });
  await page.screenshot({ path: path.join(outDir, fileName), fullPage: false });
}

async function main() {
  await fs.mkdir(outDir, { recursive: true });

  const browser = await chromium.launch({
    headless: true,
    executablePath: edgePath,
    args: ['--disable-gpu'],
  });
  const context = await browser.newContext({
    viewport: { width: 1440, height: 900 },
    deviceScaleFactor: 1,
  });
  const page = await context.newPage();

  await capturePage(page, baseUrl + 'contact.php', 'contact.png');
  await capturePage(page, baseUrl + 'login.php', 'login.png');

  await page.goto(baseUrl + 'login.php', { waitUntil: 'networkidle' });
  await page.fill('input[name="email"]', 'admin@example.com');
  await page.fill('input[name="password"]', 'admin123');
  await page.locator('form button[type="submit"]').click();
  await page.waitForLoadState('networkidle');

  await capturePage(page, baseUrl + 'posts.php?category_id=1', 'questions_filtered.png');
  await capturePage(page, baseUrl + 'post_view.php?id=10', 'question_detail.png');
  await capturePage(page, baseUrl + 'post_action.php', 'ask_question.png');
  await capturePage(page, baseUrl + 'admin/index.php', 'admin_dashboard.png');
  await capturePage(page, baseUrl + 'admin/users.php', 'admin_users.png');
  await capturePage(page, baseUrl + 'admin/categories.php', 'admin_modules.png');

  await browser.close();
}

main().catch(err => {
  console.error(err);
  process.exit(1);
});
