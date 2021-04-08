SELECT fp.`id`,
 fp.`name`,
  fp.`contract_id`,
   fp.`date`,
    fp.`author_id`,
     fp.`observers_ids`,
      fp.`department_id`,
       fp.`storages_ids`,
        fp.`products_id`,
         fp.`status`,
          fp.`end_date`,
           fp.`comment`,
            fp.`options`,
             fp.`account_id`,
              fc.`amount` AS contract_amount,
               fc.`currency` AS contract_currency,
                fc.`number` AS contract_number,
                 fc.`type_id` AS contract_type_id,
                  fco.`id` AS contractor_id,
                   fco.`name` AS contractor_name,
                    fd.`id` AS department_id,
                     fd.`name` AS department_name,
                      ft.`type`,
                       fl.`status` AS lead_status
                       FROM `fin_projects` fp
                       LEFT JOIN fin_contracts fc ON fc.id = fp.contract_id
                       LEFT JOIN fin_contractors fco ON fco.id = fc.contractor_id
                       LEFT JOIN fin_departments fd ON fd.id = fp.department_id
                       LEFT JOIN fin_types ft ON ft.id = fc.type_id
                       LEFT JOIN fin_leads fl ON fl.id = fp.lead_id
                       WHERE fp.account_id = 2
                       AND fp.lead_id != 0
                       AND lead_status = "calculation"