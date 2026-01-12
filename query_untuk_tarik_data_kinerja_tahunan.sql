SELECT
    /* ===== 2025 ===== */
    SUM(CASE WHEN YEAR(s.created_at) = 2025 THEN 1 ELSE 0 END) AS jumlah_2025,
    AVG(CASE WHEN YEAR(s.created_at) = 2025 THEN a.IU ELSE NULL END) AS IU_2025,

    /* ===== 2024 ===== */
    SUM(CASE WHEN YEAR(s.created_at) = 2024 THEN 1 ELSE 0 END) AS jumlah_2024,
    AVG(CASE WHEN YEAR(s.created_at) = 2024 THEN a.IU ELSE NULL END) AS IU_2024

FROM samples s
JOIN analisa_off_farms a
    ON a.sample_id = s.id
WHERE s.material_id IN (120, 195)
  AND YEAR(s.created_at) IN (2024, 2025)
  AND a.IU IS NOT NULL;
