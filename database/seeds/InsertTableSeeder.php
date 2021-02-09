<?php

use Illuminate\Database\Seeder;

class InsertTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => '$2y$10$zdbEqMIjHfnQAJM4gCMRwuy.EGywUu3uFi0OfGVPnjGiaPZWhTLJu',
                'remember_token' => NULL,
                'created_at' => '2021-02-08 17:31:08',
                'updated_at' => '2021-02-08 17:31:08',
                'point' => '0',
                'accumulation_point' => '0',
                'level' => '0',
                'phone' => '0912312312',
                'address' => '台北市大安區新生北路一段',
                'login_time' => '2021-02-08 17:31:08',
                'status' => 'Y',
                'admin' => 'Y',
            ],
        ]);
        DB::table('products')->insert([
            [
                'product_id' => 1,
                'product_name' => '折疊桌',
                'product_img' => '0368937_PE549760_S4.jpg',
                'product_price' => 3500,
                'product_amount' => 50,
                'product_create_time' => '2021-02-09 04:30:42',
                'product_updata_time' => '2021-02-09 05:39:07',
                'product_status' => 'Y',
                'product_description' => '方便收納的桌子',
                'product_category' => '10',
            ],
            [
                'product_id' => 2,
                'product_name' => '椅子',
                'product_img' => '0475400_PE615581_S4.jpg',
                'product_price' => 800,
                'product_amount' => 100,
                'product_create_time' => '2021-02-09 05:39:40',
                'product_updata_time' => '2021-02-09 05:39:40',
                'product_status' => 'Y',
                'product_description' => '普通的椅子',
                'product_category' => '10',
            ],
            [
                'product_id' => 3,
                'product_name' => '黑色沙發',
                'product_img' => '0728848_PE736539_S4.jpg',
                'product_price' => 8000,
                'product_amount' => 100,
                'product_create_time' => '2021-02-09 05:40:24',
                'product_updata_time' => '2021-02-09 05:40:24',
                'product_status' => 'Y',
                'product_description' => '躺起來很舒服',
                'product_category' => '10',
            ],
            [
                'product_id' => 4,
                'product_name' => '兒童椅',
                'product_img' => '0514141_PE639326_S3.jpg',
                'product_price' => 1200,
                'product_amount' => 20,
                'product_create_time' => '2021-02-09 05:41:42',
                'product_updata_time' => '2021-02-09 05:41:42',
                'product_status' => 'Y',
                'product_description' => '小孩子才坐',
                'product_category' => '10',
            ],
            [
                'product_id' => 5,
                'product_name' => '木頭餐桌',
                'product_img' => '0737105_PE740883_S4餐桌.jpg',
                'product_price' => 1500,
                'product_amount' => 10,
                'product_create_time' => '2021-02-09 05:42:17',
                'product_updata_time' => '2021-02-09 05:42:17',
                'product_status' => 'Y',
                'product_description' => '吃飯用',
                'product_category' => '10',
            ],
            [
                'product_id' => 6,
                'product_name' => '雙人沙發',
                'product_img' => '0770896_PE755642_S4沙發.jpg',
                'product_price' => 10000,
                'product_amount' => 30,
                'product_create_time' => '2021-02-09 05:42:59',
                'product_updata_time' => '2021-02-09 05:42:59',
                'product_status' => 'Y',
                'product_description' => '看起來很好坐',
                'product_category' => '10',
            ],
            [
                'product_id' => 7,
                'product_name' => '衣櫃',
                'product_img' => '0780493_PE760013_S4.jpg',
                'product_price' => 30000,
                'product_amount' => 30,
                'product_create_time' => '2021-02-09 05:43:32',
                'product_updata_time' => '2021-02-09 05:43:32',
                'product_status' => 'Y',
                'product_description' => '多功能收納',
                'product_category' => '10',
            ],
            [
                'product_id' => 8,
                'product_name' => '鯊鯊',
                'product_img' => '1608802408_10373589_S4.jpeg',
                'product_price' => 999,
                'product_amount' => 500,
                'product_create_time' => '2021-02-09 05:44:08',
                'product_updata_time' => '2021-02-09 05:44:08',
                'product_status' => 'Y',
                'product_description' => 'shrakkkk',
                'product_category' => '10',
            ],
        ]);
        DB::table('category')->insert([
            [
                'id' => 10,
                'category_name' => '未分類',
            ],
        ]);
    }
}
