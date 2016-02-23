extend

  extends:
      extend: search for same file in parent tempalte if available
      extend file/relative: searches for file regarding to current file path
      extend /file/absolute: searches for file regarding to root path

  blocks:
      block blockname: defines a block which can be modified afterwards
      block blockname: if we write this in an extending file where this block exists in the parent file,
                       it will be overwritten
      block prepend blockname: inserts content of this block before the "block blockname" block
      block wrap blockname: wraps content of the "block blockname" block with the content of our current block
      block append blockname: inserts content of this block after the "block blockname" block
