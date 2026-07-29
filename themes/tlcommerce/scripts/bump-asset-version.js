const fs = require("fs");
const path = require("path");

const versionPath = path.join(__dirname, "..", "asset-version.json");

let current = 0;
try {
  const raw = fs.readFileSync(versionPath, "utf8");
  const data = JSON.parse(raw);
  const parsed = parseInt(String(data.version || "0"), 10);
  if (!Number.isNaN(parsed) && parsed >= 0) {
    current = parsed;
  }
} catch (e) {
  // Start from 0 if file is missing or invalid
}

const next = current + 1;
const payload = { version: String(next) };

fs.writeFileSync(versionPath, JSON.stringify(payload, null, 2) + "\n", "utf8");
console.log(`[tlcommerce] asset-version bumped: ${current} → ${next}`);
