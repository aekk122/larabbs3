<?php

namespace App\Handlers;

use Image;

class ImageUploadHandler {

	// 设置允许的文件后缀
	protected $allowed_ext = ['png', 'gif', 'jpeg', 'jpg'];

	// $file-> 上传的图片
	// $folder-> 要保存的文件夹
	// $file_prefix->根据数据模型 ID 配置
	public function save($file, $folder, $file_prefix, $max_width = false) {

		// 构建存储的文件夹规则
		// 文件夹切割可以让搜索的速度更快
		// 值如：uploads/images/avatar/201809/22/
		$folder_name = "uploads/images/$folder/" . date("Ym", time()) . '/' . date("d", time()) . '/';

		// 文件具体存储的物理路径，'public_path' 获取的是 'public' 文件夹的物理物理
		// 值如：/home/vagrant/Code/larabbs/public/uploads/images/avatar/201808/22
		$upload_path = public_path() . '/' . $folder_name;

		// 获得文件后缀，以作后续的判断与拼接
		$extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

		// 编写文件名称，加上前缀与后缀
		// 值如：1_1493521050_7BVc9v9ujP.png
		$filename = $file_prefix . '_' . time() . '_' . str_random(10) . '.' . $extension;

		// 通过后缀判断文件是否合法
		if ( ! in_array($extension, $this->allowed_ext)) {
            return false;
        }

		// 保存图片
		$file->move($upload_path, $filename);

		// 如果限制了图片的宽度
		if ( $max_width && $extension != 'gif' ) {
			// 此类中封装的函数，用于裁剪
			$this->reduceSize($upload_path . $filename, $max_width);
		}

		return [
			'path' => config('app.url') . "/$folder_name$filename",
		];
	}

	public function reduceSize($file_path, $max_width) {

		// 实例化，传参为文件的磁盘物理路径
		$image = Image::make($file_path);

		// 进行大小裁剪
		$image->resize($max_width, 362, function($constraint) {

			// 设定宽度是 $max_width, 高度等比例双方缩放
			

			// 防止裁图时图片尺寸变大
			$constraint->upsize();
		});

		// 保存
		$image->save();
	}
}