preferences.php : <div class="container">

    <div class="section">
        <h2>Your Event Preferences</h2>

        <form method="POST" action="save_preferences.php">

            <label>What do you enjoy?</label>
            <textarea name="likes" placeholder="music, sports, workshops"><?= htmlspecialchars($likes) ?></textarea>

            <label>What do you NOT enjoy?</label>
            <textarea name="dislikes" placeholder="parties, loud events"><?= htmlspecialchars($dislikes) ?></textarea>

            <label>Preferred Event Style</label>
            <select name="preferred_event_style">
                <option value="">Select…</option>
                <option value="calm" <?= $stylePref === "calm" ? "selected" : "" ?>>Calm & educational</option>
                <option value="fun" <?= $stylePref === "fun" ? "selected" : "" ?>>Fun & energetic</option>
                <option value="networking" <?= $stylePref === "networking" ? "selected" : "" ?>>Networking & clubs</option>
            </select>

            <label>Preferred Time of Day</label>
            <select name="preferred_time">
                <option value="">Select…</option>
                <option value="morning" <?= $timePref === "morning" ? "selected" : "" ?>>Morning</option>
                <option value="afternoon" <?= $timePref === "afternoon" ? "selected" : "" ?>>Afternoon</option>
                <option value="evening" <?= $timePref === "evening" ? "selected" : "" ?>>Evening</option>
            </select>

            <button class="btn" type="submit">Save Preferences</button>
        </form>

        <div class="back-link">
            <a href="dashboard.php">← Back to Dashboard</a>
        </div>

    </div>
</div>